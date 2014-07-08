<?php

namespace Brick\Http;

/**
 * Represents an HTTP response to send back to the client.
 */
class Response extends Message
{
    /**
     * Mapping of Status Code to Reason Phrase.
     *
     * @var array
     */
    private static $statusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported'
    ];

    /**
     * @var integer
     */
    private $statusCode;

    /**
     * @var string
     */
    private $reasonPhrase;

    /**
     * @var \Brick\Http\Cookie[]
     */
    private $cookies = [];

    /**
     * Class constructor.
     *
     * @param string  $content    The response content.
     * @param integer $statusCode The response status code.
     * @param array   $headers    The response headers as an associative array.
     */
    public function __construct($content = '', $statusCode = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->setStatusCode($statusCode);
        $this->addHeaders($headers);
    }

    /**
     * Parses a raw response string, including headers and body, and returns a Response object.
     *
     * @param string $response
     *
     * @return \Brick\Http\Response
     *
     * @throws \RuntimeException
     */
    public static function parse($response)
    {
        $responseObject = new Response();

        if (preg_match('/^(HTTP\/[0-9]\.[0-9]) ([0-9]{3}) .*\r\n/', $response, $matches) == 0) {
            throw new \RuntimeException('Could not parse response (error 1).');
        }

        list ($line, $protocolVersion, $statusCode) = $matches;

        $responseObject->setProtocolVersion($protocolVersion);
        $responseObject->setStatusCode($statusCode);

        $response = substr($response, strlen($line));

        for (;;) {
            $pos = strpos($response, Message::CRLF);
            if ($pos === false) {
                throw new \RuntimeException('Could not parse response (error 2).');
            }

            if ($pos == 0) {
                break;
            }

            $header = substr($response, 0, $pos);

            if (preg_match('/^(\S+):\s*(.*)$/', $header, $matches) == 0) {
                throw new \RuntimeException('Could not parse response (error 3).');
            }

            list ($line, $name, $value) = $matches;

            if (strtolower($name) == 'set-cookie') {
                $responseObject->setCookie(Cookie::parse($value));
            } else {
                $responseObject->addHeader($name, $value);
            }

            $response = substr($response, strlen($line) + 2);
        }

        $body = substr($response, 2);

        $responseObject->setContent($body);

        return $responseObject;
    }

    /**
     * Returns the status code of this response.
     *
     * @return integer The status code.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the status code of this response.
     *
     * @param integer     $statusCode   The status code.
     * @param string|null $reasonPhrase The reason phrase, or null to use the default.
     */
    public function setStatusCode($statusCode, $reasonPhrase = null)
    {
        if ($reasonPhrase === null) {
            $reasonPhrase = isset(self::$statusCodes[$statusCode])
                ? self::$statusCodes[$statusCode]
                : 'UNKNOWN';
        }

        $this->statusCode = (int) $statusCode;
        $this->reasonPhrase = (string) $reasonPhrase;
    }

    /**
     * Returns the cookies currently set on this response.
     *
     * @return \Brick\Http\Cookie[]
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Sets a cookie on this response.
     *
     * @param \Brick\Http\Cookie $cookie The cookie to set.
     *
     * @return static This response.
     */
    public function setCookie(Cookie $cookie)
    {
        $this->cookies[] = $cookie;
        $this->addHeader('Set-Cookie', $cookie->toString());

        return $this;
    }

    /**
     * Removes all cookies from this response.
     *
     * @return static This response.
     */
    public function removeCookies()
    {
        $this->cookies = [];

        return $this->removeHeader('Set-Cookie');
    }

    /**
     * @param string|resource $content
     *
     * @return static This response.
     */
    public function setContent($content)
    {
        if (is_resource($content)) {
            $body = new MessageBodyResource($content);
        } else {
            $body = new MessageBodyString($content);
        }

        return $this->setBody($body);
    }

    /**
     * Returns whether this response has an informational status code, 1xx.
     *
     * @return boolean
     */
    public function isInformational()
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    /**
     * Returns whether this response has a successful status code, 2xx.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Returns whether this response has a redirection status code, 3xx.
     *
     * @return boolean
     */
    public function isRedirection()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * Returns whether this response has a client error status code, 4xx.
     *
     * @return boolean
     */
    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * Returns whether this response has a server error status code, 5xx.
     *
     * @return boolean
     */
    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * Returns whether this response has the given status code.
     *
     * @param integer $statusCode
     *
     * @return boolean
     */
    public function isStatusCode($statusCode)
    {
        return $this->statusCode === (int) $statusCode;
    }

    /**
     * Sends the response.
     *
     * This method will fail (return `false`) if the headers have been already sent.
     *
     * @return boolean Whether the response has been successfully sent.
     */
    public function send()
    {
        if (headers_sent()) {
            return false;
        }

        header($this->getStartLine());

        foreach ($this->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        echo (string) $this->body;

        flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartLine()
    {
        return sprintf('%s %d %s', $this->protocolVersion, $this->statusCode, $this->reasonPhrase);
    }
}
