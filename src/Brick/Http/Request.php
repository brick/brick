<?php

namespace Brick\Http;

use Brick\Type\Map;
use Brick\Http\Exception\HttpBadRequestException;

/**
 * Represents an HTTP request.
 */
class Request extends Message
{
    /**
     * @var array
     */
    private $query;

    /**
     * @var array
     */
    private $post;

    /**
     * @var array
     */
    private $cookies;

    /**
     * @var array
     */
    private $files;

    /**
     * @var boolean
     */
    private $isSecure;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $host;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string|null
     */
    private $queryString;

    /**
     * @var string
     */
    private $clientIp;

    /**
     * @var MessageBody
     */
    private $body;

    /**
     * Class constructor.
     *
     * @param string          $method
     * @param string          $requestUri
     * @param string          $protocolVersion
     * @param string          $host
     * @param int             $port
     * @param string          $clientIp
     * @param bool            $isSecure
     * @param array           $post
     * @param array           $cookies
     * @param array           $headers
     * @param array           $files
     * @param string|resource $body
     *
     * @throws HttpBadRequestException
     */
    private function __construct(
        $method,
        $requestUri,
        $protocolVersion,
        $host,
        $port,
        $clientIp,
        $isSecure,
        array $post,
        array $cookies,
        array $headers,
        array $files,
        $body
    ) {
        $requestUri = (string) $requestUri;

        if ($requestUri === '' || $requestUri[0] !== '/') {
            // @todo Proxy requests are valid and start with http[s]://
            throw new HttpBadRequestException('The Request URI must not be empty, and must start with a slash.');
        }

        $this->method          = strtoupper($method);
        $this->requestUri      = $requestUri;
        $this->protocolVersion = (string) $protocolVersion;
        $this->host            = (string) $host;
        $this->port            = (int)    $port;
        $this->clientIp        = (string) $clientIp;
        $this->isSecure        = (bool)   $isSecure;

        $this->path = parse_url($this->requestUri, PHP_URL_PATH);

        $this->queryString = parse_url($this->requestUri, PHP_URL_QUERY);

        if (is_string($this->queryString)) {
            parse_str($this->queryString, $query);
        } else {
            $query = [];
        }

        $headers['Host'] = $host;

        $this->query = $query;
        $this->post  = $post;
        $this->files = $files;

        $this->setCookies($cookies);

        if ($post) {
            $body = http_build_query($post);
            $headers['Content-Length'] = strlen($body);

            $this->body = new MessageBodyString($body);
        } else {
            if (is_resource($body)) {
                if (! isset($headers['Content-Length'])) {
                    $temp = fopen('php://temp', 'rb+');
                    stream_copy_to_stream($body, $temp);
                    $length = ftell($temp);
                    fseek($temp, 0);
                    $body = $temp;
                } else {
                    $length = 0;
                }

                $this->body = new MessageBodyResource($body);
            } else {
                $this->body = new MessageBodyString($body);
                $length = strlen($body);
            }

            if ($length) {
                $headers['Content-Length'] = $length;
            }
        }

        $this->addHeaders($headers);
    }

    /**
     * Creates a Request from a URL. Used for simulating HTTP requests.
     *
     * @param string $url
     * @param string $method
     * @param array  $post
     * @param array  $cookies
     * @param array  $headers
     * @param array  $files
     * @param string $clientIp
     * @param string $protocol
     * @param resource|null $body
     *
     * @return Request
     *
     * @throws HttpBadRequestException
     */
    public static function create(
        $url,
        $method = 'GET',
        array $post = [],
        array $cookies = [],
        array $headers = [],
        array $files = [],
        $clientIp = '0.0.0.0',
        $protocol = self::HTTP_1_1,
        $body = null
    ) {
        $urlParts = parse_url($url);

        if (! isset($urlParts['scheme'])) {
            throw new HttpBadRequestException('The URL provided has no scheme');
        }

        $scheme = strtolower($urlParts['scheme']);
        if (! in_array($scheme, ['http', 'https'])) {
            throw new HttpBadRequestException('Invalid scheme: ' . $scheme);
        }

        $isSecure = ($scheme == 'https');

        if (! isset($urlParts['host'])) {
            throw new HttpBadRequestException('The URL provided has no host');
        }

        $host = $urlParts['host'];

        $port = isset($urlParts['port']) ? $urlParts['port'] : self::getStandardPort($isSecure);

        $requestUri = isset($urlParts['path']) ? $urlParts['path'] : '/';
        if (isset($urlParts['query'])) {
            $requestUri .= '?' . $urlParts['query'];
        }

        if ($body === null) {
            $body = fopen('php://memory', 'r');
        }

        $request = new Request(
            $method,
            $requestUri,
            $protocol,
            $host,
            $port,
            $clientIp,
            $isSecure,
            $post,
            $cookies,
            $headers,
            $files,
            $body
        );

        return $request;
    }

    /**
     * Returns a Request object representing the current request.
     *
     * The query string data is purposefully parsed from the REQUEST_URI, and not from the $_GET superglobal,
     * to provide a consistent behaviour even when mod_rewrite is in use.
     *
     * @todo use $_SERVER['REQUEST_SCHEME'] when available?
     *
     * @param boolean $trustProxy Whether to trust X-Forwarded-* headers.
     *
     * @return Request
     * @throws HttpBadRequestException
     */
    public static function getCurrent($trustProxy = false)
    {
        $server = new Map($_SERVER, false);

        $isSecure   = $server->has('HTTPS') && in_array(strtolower($server->get('HTTPS')), ['on', '1']);
        $method     = $server->get('REQUEST_METHOD');
        $requestUri = $server->get('REQUEST_URI');
        $protocol   = $server->get('SERVER_PROTOCOL');
        $clientIp   = $server->get('REMOTE_ADDR');

        $post    = $_POST;
        $cookies = $_COOKIE;
        $files   = UploadedFileMap::createFromFilesGlobal($_FILES)->toArray();

        $host = null;

        $headers = self::getHeadersFromGlobals();

        foreach ($headers as $name => $value) {
            if (strtolower($name) == 'host') {
                $host = $value;
            }
        }

        if ($host === null) {
            $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_ADDR'];
        }

        preg_match('/^(.*?)(?:\:([0-9]+))?$/', $host, $matches);

        $host = $matches[1];
        $port = isset($matches[2]) ? $matches[2] : self::getStandardPort($isSecure);

        $body = fopen('php://input', 'rb');

        if ($trustProxy) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ips = preg_split('/,\s*/', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = array_pop($ips);

                $clientIp = $ip;
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_PORT'])) {
                $port = $_SERVER['HTTP_X_FORWARDED_PORT'];
            }

            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $isSecure = $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';
            }
        }

        return new Request(
            $method,
            $requestUri,
            $protocol,
            $host,
            $port,
            $clientIp,
            $isSecure,
            $post,
            $cookies,
            $headers,
            $files,
            $body
        );
    }

    /**
     * Returns an associative array of headers from the current request.
     *
     * @return array
     */
    private static function getHeadersFromGlobals()
    {
        if (is_array($headers = apache_request_headers())) {
            return $headers;
        }

        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[self::normalizeHeaderName(substr($key, 5))] = $value;
            }
            elseif (in_array($key, ['CONTENT_LENGTH', 'CONTENT_TYPE'])) {
                // These variables are not prefixed with HTTP_
                $headers[self::normalizeHeaderName($key)] = $value;
            }
        }

        return $headers;
    }

    /**
     * @param boolean $secure
     *
     * @return integer
     */
    private static function getStandardPort($secure)
    {
        return $secure ? 443 : 80;
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
     * Returns the uploaded file(s).
     *
     * @param string|null $name The name of the file input field, or null to return all files.
     *
     * @return \Brick\Http\UploadedFile|array|null
     */
    public function getFile($name = null)
    {
        if ($name === null) {
            return $this->files;
        }

        return $this->resolvePath($this->files, $name);
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
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return boolean
     */
    public function isMethodSafe()
    {
        return $this->method === 'GET' || $this->method === 'HEAD';
    }

    /**
     * Returns whether this request method matches the given one.
     *
     * Example: $request->is('post');
     *
     * @param string $method The method to test this request against, case-insensitive.
     *
     * @return boolean
     */
    public function is($method)
    {
        return $this->method === strtoupper($method);
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->isSecure ? 'https' : 'http';
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return array
     */
    public function getHostParts()
    {
        return explode('.', $this->host);
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getPathParts()
    {
        return preg_split('|/|', $this->path, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @return bool
     */
    public function hasQueryString()
    {
        return $this->queryString !== null;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString === null ? '' : $this->queryString;
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }

    /**
     * @return boolean
     */
    private function isStandardPort()
    {
        return $this->port == self::getStandardPort($this->isSecure);
    }

    /**
     * @return string
     */
    public function getUrlBase()
    {
        $url  = sprintf('%s://%s', $this->getScheme(), $this->host);

        return $this->isStandardPort() ? $url : $url . ':' . $this->port;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getUrlBase() . $this->requestUri;
    }

    /**
     * Returns the request URL with update query parameters.
     *
     * @param array $parameters The query parameters to add/replace.
     *
     * @return string The updated URL.
     */
    public function getUpdatedUrl(array $parameters)
    {
        $url = $this->getUrlBase() . $this->path;

        $parameters += $this->query;

        if ($parameters) {
            $url .= '?' . http_build_query($parameters);
        }

        return $url;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->isSecure;
    }

    /**
     * @return string
     */
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @return string|null
     */
    public function getUserAgent()
    {
        return $this->getFirstHeader('User-Agent');
    }

    /**
     * @return integer
     */
    public function getContentLength()
    {
        if ($this->hasHeader('Content-Length')) {
            return (int) $this->getFirstHeader('Content-Length');
        }

        return 0;
    }

    /**
     * @return array
     */
    public function getAcceptLanguage()
    {
        if (! $this->hasHeader('Accept-Language')) {
            return [];
        }

        $result = [];
        $languages = preg_split('/,\s*/', $this->getFirstHeader('Accept-Language'));
        $pattern = '/^([a-z]+(?:[\-_][a-z]+)*)(?:;\s*q=(0(?:\.[0-9]+)?|1(?:\.0+)?))?$/i';

        $weight = $total = count($languages);

        foreach ($languages as $language) {
            if (preg_match($pattern, $language, $matches) == 0) {
                continue;
            }

            $quality = isset($matches[2]) ? (float) $matches[2] : 1.0;
            $quality = $quality * $total + $weight;

            $result[$matches[1]] = $quality;
            $weight--;
        }

        arsort($result);

        return array_keys($result);
    }

    /**
     * @return boolean
     */
    public function isAjax()
    {
        return $this->getFirstHeader('X-Requested-With') == 'XMLHttpRequest';
    }

    /**
     * @param array $cookies
     *
     * @return void
     */
    public function setCookies(array $cookies)
    {
        $this->cookies = $cookies;

        if ($cookies) {
            $cookie = str_replace('&', '; ', http_build_query($cookies));
            $this->setHeader('Cookie', $cookie);
        }
    }
}
