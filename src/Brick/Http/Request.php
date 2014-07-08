<?php

namespace Brick\Http;

/**
 * Represents an HTTP request received by the server.
 */
class Request extends Message
{
    const PREFER_HTTP_HOST   = 0; // Prefer HTTP_HOST, fall back to SERVER_NAME and SERVER_PORT.
    const PREFER_SERVER_NAME = 1; // Prefer SERVER_NAME and SERVER_PORT, fall back to HTTP_HOST.
    const ONLY_HTTP_HOST     = 2; // Only use HTTP_HOST if available, ignore SERVER_NAME and SERVER_PORT.
    const ONLY_SERVER_NAME   = 3; // Only use SERVER_NAME and SERVER_PORT if available, ignore HTTP_HOST.

    /**
     * The standard HTTP methods.
     *
     * According to RFC 2616, all methods should be treated in a case-sensitive way.
     * However, many implementations still do not comply with the spec and treat the method as case-insensitive.
     *
     * For maximum compatibility, this implementation follows the pragmatic principle of XMLHttpRequest:
     * - If the method case-insensitively matches a standard method, consider it case-insensitive.
     * - If the method is not in this list, consider it case-sensitive.
     *
     * @see http://www.w3.org/TR/XMLHttpRequest/
     *
     * @var array
     */
    private static $standardMethods = [
        'CONNECT',
        'DELETE',
        'GET',
        'HEAD',
        'OPTIONS',
        'POST',
        'PUT',
        'TRACE',
        'TRACK'
    ];

    /**
     * @var array
     */
    private $query = [];

    /**
     * @var array
     */
    private $post = [];

    /**
     * @var array
     */
    private $cookies = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var boolean
     */
    private $isSecure = false;

    /**
     * The request method.
     *
     * @var string
     */
    private $method = 'GET';

    /**
     * @var string
     */
    private $host = 'localhost';

    /**
     * @var integer
     */
    private $port = 80;

    /**
     * The Request-URI.
     *
     * @var string
     */
    private $requestUri = '/';

    /**
     * The part before the `?` in the Request-URI.
     *
     * @var string
     */
    private $path = '/';

    /**
     * The part after the `?` in the Request-URI.
     *
     * @var string
     */
    private $queryString = '';

    /**
     * @var string
     */
    private $clientIp = '0.0.0.0';

    /**
     * Returns a Request object representing the current request.
     *
     * Note that due to the way PHP works, the request body will be empty
     * when the Content-Type is multipart/form-data.
     *
     * Note that the query string data is purposefully parsed from the REQUEST_URI,
     * and not just taken from the $_GET superglobal.
     * This is to provide a consistent behaviour even when mod_rewrite is in use.
     *
     * @param boolean $trustProxy     Whether to trust X-Forwarded-* headers.
     * @param integer $hostPortSource One of the PREFER_* or ONLY_* constants.
     *
     * @return Request
     */
    public static function getCurrent($trustProxy = false, $hostPortSource = self::PREFER_HTTP_HOST)
    {
        $request = new Request();

        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1') {
                $request->isSecure = true;
                $request->port = 443;
            }
        }

        $httpHost = null;
        $httpPort = null;

        $serverName = null;
        $serverPort = null;

        if (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
            $pos = strrpos($host, ':');

            if ($pos === false) {
                $httpHost = $host;
                $httpPort = $request->port;
            } else {
                $httpHost = substr($host, 0, $pos);
                $httpPort = (int) substr($host, $pos + 1);
            }
        }

        if (isset($_SERVER['SERVER_NAME'])) {
            $serverName = $_SERVER['SERVER_NAME'];
        }

        if (isset($_SERVER['SERVER_PORT'])) {
            $serverPort = (int) $_SERVER['SERVER_PORT'];
        }

        $host = null;
        $port = null;

        switch ($hostPortSource) {
            case self::PREFER_HTTP_HOST:
                $host = ($httpHost !== null) ? $httpHost : $serverName;
                $port = ($httpPort !== null) ? $httpPort : $serverPort;
                break;

            case self::PREFER_SERVER_NAME:
                $host = ($serverName !== null) ? $serverName : $httpHost;
                $port = ($serverPort !== null) ? $serverPort : $httpPort;
                break;

            case self::ONLY_HTTP_HOST:
                $host = $httpHost;
                $port = $httpPort;
                break;

            case self::ONLY_SERVER_NAME:
                $host = $serverName;
                $port = $serverPort;
                break;
        }

        if ($host !== null) {
            $request->host = $host;
        }

        if ($port !== null) {
            $request->port = $port;
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $request->method = $_SERVER['REQUEST_METHOD'];
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $request->setRequestUri($_SERVER['REQUEST_URI']);
        }

        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $request->protocolVersion = $_SERVER['SERVER_PROTOCOL'];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $request->clientIp = $_SERVER['REMOTE_ADDR'];
        }

        $request->headers = self::getCurrentRequestHeaders();

        $request->post    = $_POST;
        $request->cookies = $_COOKIE;
        $request->files   = UploadedFileMap::createFromFilesGlobal($_FILES);

        if (isset($_SERVER['CONTENT_LENGTH']) || isset($_SERVER['HTTP_TRANSFER_ENCODING'])) {
            $request->body = new MessageBodyResource(fopen('php://input', 'rb'));
        }

        if ($trustProxy) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = preg_split('/,\s*/', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $request->clientIp = array_pop($ips);
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
                $request->host = $_SERVER['HTTP_X_FORWARDED_HOST'];
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_PORT'])) {
                $request->port = (int) $_SERVER['HTTP_X_FORWARDED_PORT'];
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $request->isSecure = $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https';
            }
        }

        return $request;
    }

    /**
     * Returns the current request headers.
     *
     * The headers are prepared in the format expected by `Message::$headers`:
     *   - keys are lowercased
     *   - values are arrays of strings
     *
     * @return array
     */
    private static function getCurrentRequestHeaders()
    {
        $headers = [];

        if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();

            if ($requestHeaders) {
                foreach ($requestHeaders as $key => $value) {
                    $key = strtolower($key);
                    $headers[$key] = [$value];
                }

                return $headers;
            }
        }

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
            } elseif ($key !== 'CONTENT_TYPE' && $key !== 'CONTENT_LENGTH') {
                continue;
            }

            $key = strtolower(str_replace('_', '-', $key));
            $headers[$key] = [$value];
        }

        return $headers;
    }

    /**
     * Returns the query parameter(s).
     *
     * You can optionally use a full path to the query parameter:
     *
     *     $request->getQuery('foo[bar]');
     *
     * Or even simpler:
     *
     *     $request->getQuery('foo.bar');
     *
     * @param string|null $name The parameter name, or null to return all query parameters.
     *
     * @return string|array|null The query parameter(s), or null if the path is not found.
     */
    public function getQuery($name = null)
    {
        if ($name === null) {
            return $this->query;
        }

        return $this->resolvePath($this->query, $name);
    }

    /**
     * Sets the query parameters.
     *
     * @param array $query The associative array of parameters.
     *
     * @return static This request.
     */
    public function setQuery(array $query)
    {
        $this->query = $query;
        $this->queryString = http_build_query($query);

        $this->requestUri = $this->path;

        if ($this->queryString != '') {
            $this->requestUri .= '?' . $this->queryString;
        }

        return $this;
    }

    /**
     * Returns the post parameter(s).
     *
     * @param string|null $name The parameter name, or null to return all post parameters.
     *
     * @return string|array|null The post parameter(s), or null if the path is not found.
     */
    public function getPost($name = null)
    {
        if ($name === null) {
            return $this->post;
        }

        return $this->resolvePath($this->post, $name);
    }

    /**
     * Sets the post parameter.
     *
     * This will set a request body with the URL-encoded data,
     * unless the Content-Type of this request is multipart/form-data,
     * in which case the body is left as is.
     *
     * @param array $post The associative array of parameters.
     *
     * @return static This request.
     */
    public function setPost(array $post)
    {
        $this->post = $post;

        if (! $this->isContentType('multipart/form-data')) {
            $body = http_build_query($post);
            $body = new MessageBodyString($body);

            $this->setBody($body);
            $this->setHeader('Content-Type', 'x-www-form-urlencoded');
        }

        return $this;
    }

    /**
     * Returns the uploaded file by the given name.
     *
     * If no uploaded file exists by that name,
     * or if the name maps to an array of uploaded files,
     * this method returns NULL.
     *
     * @param string $name The name of the file input field.
     *
     * @return \Brick\Http\UploadedFile|null The uploaded file, or NULL if not found.
     */
    public function getFile($name)
    {
        $file = $this->resolvePath($this->files, $name);

        if ($file instanceof UploadedFile) {
            return $file;
        }

        return null;
    }

    /**
     * Returns the uploaded files under the given name.
     *
     * If no uploaded files by that name exist, an empty array is returned.
     *
     * @param string $name The name of the file input fields.
     *
     * @return \Brick\Http\UploadedFile[] The uploaded files.
     */
    public function getFiles($name = null)
    {
        if ($name === null) {
            return $this->files;
        }

        $files = $this->resolvePath($this->files, $name);

        if ($files instanceof UploadedFile) {
            return [$files];
        }

        if (is_array($files)) {
            return array_filter($files, function ($file) {
                return $file instanceof UploadedFile;
            });
        }

        return [];
    }

    /**
     * Sets the uploaded files.
     *
     * This will replace the message body, if any, with an empty body.
     * This is in line with the values available when dealing with a multipart request in PHP.
     *
     * @param array $files An associative array of uploaded files.
     *
     * @return static This request.
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
        $this->body  = new MessageBodyString('');

        $this->setHeaders([
            'Content-Type'   => 'multipart/form-data',
            'Content-Length' => '0'
        ]);

        return $this;
    }

    /**
     * Returns the cookie(s).
     *
     * @param string|null $name The cookie name, or null to return all cookies.
     *
     * @return string|array|null The cookie value(s), or null if the path is not found.
     */
    public function getCookie($name = null)
    {
        if ($name === null) {
            return $this->cookies;
        }

        return $this->resolvePath($this->cookies, $name);
    }

    /**
     * Adds cookies to this request.
     *
     * Existing cookies with the same name will be replaced.
     *
     * @param array $cookies An associative array of cookies.
     *
     * @return static This request.
     */
    public function addCookies(array $cookies)
    {
        return $this->setCookies($cookies + $this->cookies);
    }

    /**
     * Sets the cookies for this request.
     *
     * All existing cookies will be replaced.
     *
     * @param array $cookies An associative array of cookies.
     *
     * @return static This request.
     */
    public function setCookies(array $cookies)
    {
        $this->cookies = $cookies;

        if ($cookies) {
            $cookie = str_replace('&', '; ', http_build_query($cookies));
            $this->setHeader('Cookie', $cookie);
        } else {
            $this->removeHeader('Cookie');
        }

        return $this;
    }

    /**
     * @param array  $value
     * @param string $path
     *
     * @return mixed
     */
    private function resolvePath(array $value, $path)
    {
        $path = preg_replace('/\[(.*?)\]/', '.$1', $path);
        $path = explode('.', $path);

        foreach ($path as $item) {
            if (is_array($value) && isset($value[$item])) {
                $value = $value[$item];
            } else {
                return null;
            }
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartLine()
    {
        return sprintf('%s %s %s', $this->method, $this->requestUri, $this->protocolVersion);
    }

    /**
     * Returns the request method, such as GET or POST.
     *
     * @return string The request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the request method.
     *
     * If the method case-insensitively matches a standard method, it will be converted to uppercase.
     *
     * @param string $method The new request method.
     *
     * @return static This request.
     */
    public function setMethod($method)
    {
        $this->method = $this->fixMethodCase($method);

        return $this;
    }

    /**
     * Returns whether this request method matches the given one.
     *
     * Example: $request->isMethod('POST');
     *
     * If the method case-insensitively matches a standard method, the comparison will be case-insensitive.
     * Otherwise, the comparison will be case-sensitive.
     *
     * @param string $method The method to test this request against.
     *
     * @return boolean True if this request matches the method, false otherwise.
     */
    public function isMethod($method)
    {
        return $this->method === $this->fixMethodCase($method);
    }

    /**
     * Returns whether this request method is GET or HEAD.
     *
     * @return boolean
     */
    public function isMethodSafe()
    {
        return $this->method === 'GET' || $this->method === 'HEAD';
    }

    /**
     * Fixes a method case.
     *
     * @see \Brick\Http\Request::$standardMethods
     *
     * @param string $method
     *
     * @return string
     */
    private function fixMethodCase($method)
    {
        $upperMethod = strtoupper($method);
        $isStandard = in_array($upperMethod, self::$standardMethods, true);

        return $isStandard ? $upperMethod : $method;
    }

    /**
     * Returns the request scheme, http or https.
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->isSecure ? 'https' : 'http';
    }

    /**
     * Sets the request scheme.
     *
     * @param string $scheme The new request scheme.
     *
     * @return static This request.
     *
     * @throws \InvalidArgumentException If the scheme is not http or https.
     */
    public function setScheme($scheme)
    {
        $scheme = strtolower($scheme);

        if ($scheme === 'http') {
            $this->isSecure = false;
        } elseif ($scheme === 'https') {
            $this->isSecure = true;
        } else {
            throw new \InvalidArgumentException('The scheme must be http or https.');
        }

        return $this;
    }

    /**
     * Returns the host name of this request.
     *
     * @return string The host name.
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Returns the parts of the host name separated by dots, as an array.
     *
     * Example: `www.example.com` => `['www', 'example', 'com']`
     *
     * @return array The host parts.
     */
    public function getHostParts()
    {
        return explode('.', $this->host);
    }

    /**
     * Sets the host name of this request.
     *
     * This will update the Host header accordingly.
     *
     * @param string $host The new host name.
     *
     * @return static This request.
     */
    public function setHost($host)
    {
        $this->host = (string) $host;

        return $this->updateHostHeader();
    }

    /**
     * Returns the port number of this request.
     *
     * @return integer The port number.
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Sets the port number of this request.
     *
     * This will update the Host header accordingly.
     *
     * @param integer $port The new port number.
     *
     * @return static This request.
     */
    public function setPort($port)
    {
        $this->port = (int) $port;

        return $this->updateHostHeader();
    }

    /**
     * Updates the Host header from the values of host and port.
     *
     * @return static This request.
     */
    private function updateHostHeader()
    {
        $host = $this->host;
        $standardPort = $this->isSecure ? 443 : 80;

        if ($this->port !== $standardPort) {
            $host .= ':' . $this->port;
        }

        return $this->setHeader('Host', $host);
    }

    /**
     * Returns the request path.
     *
     * This does not include the query string.
     *
     * Example: `/user/profile`
     *
     * @return string The request path.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the parts of the path separated by slashes, as an array.
     *
     * Example: `/user/profile` => `['user', 'profile']`
     *
     * @return array
     */
    public function getPathParts()
    {
        return preg_split('|/|', $this->path, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Sets the request path.
     *
     * Example: `/user/profile`
     *
     * @param string $path The new request path.
     *
     * @return static This request.
     *
     * @throws \InvalidArgumentException If the path is not valid.
     */
    public function setPath($path)
    {
        if (strpos($path, '?') !== false) {
            throw new \InvalidArgumentException('The request path must not contain a query string.');
        }

        $this->path = (string) $path;

        return $this->updateRequestUri();
    }

    /**
     * Returns the query string.
     *
     * The query string is the part after the `?` in the URL.
     *
     * If this request has no query string, an empty string is returned.
     *
     * @return string The query string.
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Sets the query string.
     *
     * @param string $queryString The new query string.
     *
     * @return static This request.
     */
    public function setQueryString($queryString)
    {
        $this->queryString = (string) $queryString;
        parse_str($this->queryString, $this->query);

        return $this->updateRequestUri();
    }

    /**
     * Updates the request URI from the values of path and query string.
     *
     * @return static This request.
     */
    private function updateRequestUri()
    {
        $this->requestUri = $this->path;

        if ($this->queryString != '') {
            $this->requestUri .= '?' . $this->queryString;
        }

        return $this;
    }

    /**
     * Returns the request URI.
     *
     * @return string The request URI.
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * Sets the request URI.
     *
     * This will update the request path, query string, and query parameters.
     *
     * @param string $requestUri
     *
     * @return static
     */
    public function setRequestUri($requestUri)
    {
        $requestUri = (string) $requestUri;
        $pos = strpos($requestUri, '?');

        if ($pos === false) {
            $this->path = $requestUri;
            $this->queryString = '';
            $this->query = [];
        } else {
            $this->path = substr($requestUri, 0, $pos);
            $queryString = substr($requestUri, $pos + 1);

            if ($queryString === false) {
                $this->queryString = '';
                $this->query = [];
            } else {
                $this->queryString = $queryString;
                parse_str($this->queryString, $this->query);
            }
        }

        $this->requestUri = $requestUri;

        return $this;
    }

    /**
     * Returns the URL of this request.
     *
     * @return string The request URL.
     */
    public function getUrl()
    {
        return $this->getUrlBase() . $this->requestUri;
    }

    /**
     * Returns the scheme and host name of this request.
     *
     * @return string The base URL.
     */
    public function getUrlBase()
    {
        $url = sprintf('%s://%s', $this->getScheme(), $this->host);
        $isStandardPort = ($this->port === ($this->isSecure ? 443 : 80));

        return $isStandardPort ? $url : $url . ':' . $this->port;
    }

    /**
     * Sets the request URL.
     *
     * @param string $url The new URL.
     *
     * @return static This request.
     *
     * @throws \InvalidArgumentException If the URL is not valid.
     */
    public function setUrl($url)
    {
        $components = parse_url($url);

        if ($components === false) {
            throw new \InvalidArgumentException('The URL provided is not valid.');
        }

        if (! isset($components['scheme'])) {
            throw new \InvalidArgumentException('The URL must have a scheme.');
        }

        $scheme = strtolower($components['scheme']);

        if ($scheme !== 'http' && $scheme !== 'https') {
            throw new \InvalidArgumentException(sprintf('The URL scheme "%s" is not acceptable.', $scheme));
        }

        $isSecure = ($scheme === 'https');

        if (! isset($components['host'])) {
            throw new \InvalidArgumentException('The URL must have a host name.');
        }

        $host = $components['host'];

        if (isset($components['port'])) {
            $port = $components['port'];
            $hostHeader = $host . ':' . $port;
        } else {
            $port = ($isSecure ? 443 : 80);
            $hostHeader = $host;
        }

        $this->path = isset($components['path']) ? $components['path'] : '/';
        $requestUri = $this->path;

        if (isset($components['query'])) {
            $this->queryString = $components['query'];
            parse_str($this->queryString, $this->query);
            $requestUri .= '?' . $this->queryString;
        } else {
            $this->queryString = '';
            $this->query = [];
        }

        $this->setHeader('Host', $hostHeader);

        $this->host = $host;
        $this->port = $port;
        $this->isSecure = $isSecure;
        $this->requestUri = $requestUri;

        return $this;
    }

    /**
     * Returns whether this request is sent over a secure connection (HTTPS).
     *
     * @return boolean True if the request is secure, false otherwise.
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * Sets whether this request is sent over a secure connection.
     *
     * @param boolean $isSecure True to mark the request as secure, false to mark it as not secure.
     *
     * @return static This request.
     */
    public function setSecure($isSecure)
    {
        $isSecure = (bool) $isSecure;
        $isStandardPort = ($this->port === ($this->isSecure ? 443 : 80));

        if ($isStandardPort) {
            $this->port = $isSecure ? 443 : 80;
        }

        $this->isSecure = $isSecure;

        return $this;
    }

    /**
     * Returns the client IP address.
     *
     * @return string The IP address.
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * Sets the client IP address.
     *
     * @param string $ip The new IP address.
     *
     * @return static This request.
     */
    public function setClientIp($ip)
    {
        $this->clientIp = (string) $ip;

        return $this;
    }

    /**
     * Returns the languages accepted by the client.
     *
     * This method parses the Accept-Language header,
     * and returns an associative array where keys are language tags,
     * and values are the associated weights in the range [0-1]. Highest weights are returned first.
     *
     * @return array The accepted languages.
     */
    public function getAcceptLanguage()
    {
        return $this->parseQualityValues($this->getHeader('Accept-Language'));
    }

    /**
     * Parses quality values as defined per RFC 7231 § 5.3.1.
     *
     * @param string $header The header value as a string.
     *
     * @return array The parsed values as an associative array mapping the string to the quality value.
     */
    private function parseQualityValues($header)
    {
        $values = $this->parseHeaderParameters($header);

        $result = [];

        $count = count($values);
        $position = $count - 1;

        foreach ($values as $value => $parameters) {
            $parameters = array_change_key_case($parameters, CASE_LOWER);

            if (isset($parameters['q'])) {
                if (preg_match('/^((?:0\.?[0-9]{0,3})|(?:1\.?0{0,3}))$/', $parameters['q']) === 0) {
                    continue;
                }

                $quality = (float) $parameters['q'];
            } else {
                $quality = 1.0;
            }

            $weight = $position + $count * (int) ($quality * 1000.0);

            $result[] = [$value, $quality, $weight];

            $position--;
        }

        usort($result, function(array $a, array $b) {
            return $b[2] - $a[2];
        });

        $values = [];

        foreach ($result as $value) {
            $values[$value[0]] = $value[1];
        }

        return $values;
    }

    /**
     * Parses a header with multiple values and optional parameters.
     *
     * Example: text/html; charset=utf8, text/xml => ['text/html' => ['charset' => 'utf8'], 'text/xml' => []]
     *
     * @param string $header The header to parse.
     *
     * @return array An associative array of values and theirs parameters.
     */
    private function parseHeaderParameters($header)
    {
        $result = [];
        $values = explode(',', $header);

        foreach ($values as $parts) {
            $parameters = [];
            $parts = explode(';', $parts);
            $item = trim(array_shift($parts));

            if ($item === '') {
                continue;
            }

            foreach ($parts as $part) {
                if (preg_match('/^\s*([^\=]+)\=(.*?)\s*$/', $part, $matches) === 0) {
                    continue;
                }

                $parameters[$matches[1]] = $matches[2];
            }

            $result[$item] = $parameters;
        }

        return $result;
    }

    /**
     * Returns whether this request is sent with an XMLHttpRequest object.
     *
     * @return boolean True if the request is AJAX, false otherwise.
     */
    public function isAjax()
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }
}
