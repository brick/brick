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
     * @var ParameterMap
     */
    private $query;

    /**
     * @var ParameterMap
     */
    private $post;

    /**
     * @var CookieMap
     */
    private $cookies;

    /**
     * @var UploadedFileMap
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
    private $protocolVersion;

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
     * @param ParameterMap    $post
     * @param CookieMap       $cookies
     * @param array           $headers
     * @param UploadedFileMap $files
     * @param string|resource $body
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
        ParameterMap $post,
        CookieMap $cookies,
        array $headers,
        UploadedFileMap $files,
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

        $this->query   = new ParameterMap($query);
        $this->post    = $post;
        $this->cookies = $cookies;
        $this->files   = $files;

        if ($post->toArray()) {
            $body = http_build_query($post->toArray());
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
     * @return Request
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
            new ParameterMap($post),
            new CookieMap($cookies),
            $headers,
            new UploadedFileMap($files),
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

        $protocol = preg_replace('!^HTTP/!', '', $protocol);

        $post    = new ParameterMap($_POST);
        $cookies = new CookieMap($_COOKIE);
        $files   = UploadedFileMap::createFromFilesGlobal($_FILES);

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
     * @param boolean $https
     * @return integer
     */
    private static function getStandardPort($https)
    {
        return $https ? 443 : 80;
    }

    /**
     * Returns whether this request has the given query parameter.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasQuery($name)
    {
        return $this->query->has($name);
    }

    /**
     * Returns one or all query parameters.
     *
     * @param string|null $name The parameter name, or null to return all parameters as an associative array.
     *
     * @return string|array|null The query parameter(s), or null if the named parameter is not present.
     */
    public function getQuery($name = null)
    {
        if ($name === null) {
            return $this->query->toArray();
        } elseif ($this->query->has($name)) {
            return $this->query->get($name);
        } else {
            return null;
        }
    }

    /**
     * Returns whether this request has the given post parameter.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasPost($name)
    {
        return $this->post->has($name);
    }

    /**
     * Returns one or all post parameters.
     *
     * @param string|null $name The parameter name, or null to return all parameters as an associative array.
     *
     * @return string|array|null The post parameter(s), or null if the named parameter is not present.
     */
    public function getPost($name = null)
    {
        if ($name === null) {
            return $this->post->toArray();
        } elseif ($this->post->has($name)) {
            return $this->post->get($name);
        } else {
            return null;
        }
    }

    /**
     * Returns one or all cookies.
     *
     * @param string|null $name The cookie name, or null to return all cookies as an associative array.
     *
     * @return string|array|null The cookie value(s), or null is the named cookie is not present.
     */
    public function getCookie($name = null)
    {
        if ($name === null) {
            return $this->cookies->toArray();
        } elseif ($this->cookies->has($name)) {
            return $this->cookies->get($name);
        } else {
            return null;
        }
    }

    /**
     * Returns the semicolon-separated cookies as they should appear in the Cookie header.
     *
     * @return string
     */
    public function getCookieString()
    {
        return str_replace('&', '; ', http_build_query($this->cookies->toArray()));
    }

    /**
     * Returns the request header as a string.
     *
     * @return string
     */
    public function getHeaderString()
    {
        $result = sprintf('%s %s HTTP/%s' . Message::CRLF, $this->method, $this->requestUri, $this->protocolVersion);

        foreach ($this->getAllHeaders() as $header) {
            $result .= $header->toString() . Message::CRLF;
        }

        if ($this->cookies->count()) {
            $result .= sprintf('Cookie: %s' . Message::CRLF, $this->getCookieString());
        }

        $result .= Message::CRLF;

        return $result;
    }

    /**
     * @return UploadedFileMap
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader()
    {
        return $this->getHeaderString();
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
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @return boolean
     */
    public function isMethodSafe()
    {
        return $this->isGet() || $this->isHead();
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

        $parameters += $this->query->toArray();

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
     * @return boolean
     */
    public function isGet()
    {
        return $this->method == 'GET';
    }

    /**
     * @return boolean
     */
    public function isHead()
    {
        return $this->method == 'HEAD';
    }

    /**
     * @return boolean
     */
    public function isPost()
    {
        return $this->method == 'POST';
    }

    /**
     * @return boolean
     */
    public function isPut()
    {
        return $this->method == 'PUT';
    }

    /**
     * @return boolean
     */
    public function isDelete()
    {
        return $this->method == 'DELETE';
    }

    /**
     * @return boolean
     */
    public function isOptions()
    {
        return $this->method == 'OPTIONS';
    }

    /**
     * @return boolean
     */
    public function isTrace()
    {
        return $this->method == 'TRACE';
    }

    /**
     * @return boolean
     */
    public function isConnect()
    {
        return $this->method == 'CONNECT';
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
     * @todo breaks the immutability, might not be kept.
     *
     * @param array $cookies
     *
     * @return void
     */
    public function setCookies(array $cookies)
    {
        $this->cookies = new CookieMap($cookies);
    }
}
