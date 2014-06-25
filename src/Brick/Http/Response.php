<?php

namespace Brick\Http;

/**
 * Represents an HTTP response.
 */
class Response extends Message
{
    /**
     * Mapping of Status Code to Reason Phrase.
     *
     * @var array
     */
    private $statusCodes = [
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
     * @var string
     */
    private $protocolVersion = Message::HTTP_1_0;

    /**
     * @todo should be a body object, just like RequestBody (can be shared)
     *
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $statusCode;

    /**
     * @var string
     */
    private $reasonPhrase;

    /**
     * @var Cookie[]
     */
    private $cookies = [];

    /**
     * Class constructor.
     *
     * @param string  $content    The response content.
     * @param integer $statusCode The response status code.
     * @param array   $headers    The response headers as an associative array.
     */
    public function __construct($content = '', $statusCode = 200, $headers = [])
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

        if (preg_match('/^HTTP\/([0-9]\.[0-9]) ([0-9]{3}) .*\r\n/', $response, $matches) == 0) {
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
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param integer     $statusCode   The status code.
     * @param string|null $reasonPhrase The reason phrase, or null to use the default.
     */
    public function setStatusCode($statusCode, $reasonPhrase = null)
    {
        if ($reasonPhrase === null) {
            $reasonPhrase = isset($this->statusCodes[$statusCode])
                ? $this->statusCodes[$statusCode]
                : 'UNKNOWN';
        }

        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     *
     * @return Response
     */
    public function setProtocolVersion($version)
    {
        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * @param Cookie $cookie
     *
     * @return Response
     */
    public function setCookie(Cookie $cookie)
    {
        $this->cookies[] = $cookie;
        $this->addHeader('Set-Cookie', $cookie->toString());

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Response
     */
    public function setContent($content)
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isInformational()
    {
        return $this->statusCode >= 100 && $this->statusCode < 200;
    }

    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * @return boolean
     */
    public function isRedirection()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * @return boolean
     */
    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * @return boolean
     */
    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * @return boolean
     */
    public function isOk()
    {
        return 200 === $this->statusCode;
    }

    /**
     * @return boolean
     */
    public function isForbidden()
    {
        return 403 === $this->statusCode;
    }

    /**
     * @return boolean
     */
    public function isNotFound()
    {
        return 404 === $this->statusCode;
    }

    /**
     * @return Cookie[]
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Sends the response.
     *
     * Will fail if the headers have been already sent.
     *
     * @return boolean Whether the response has been successfully sent.
     */
    public function send()
    {
        if (headers_sent()) {
            return false;
        }

        // Send the status line.
        header($this->getStatusLine());

        // Send the general headers.
        foreach ($this->getAllHeaders() as $header) {
            header($header->toString(), false);
        }

        // Send the cookies.
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie->getName(),
                $cookie->getValue(),
                $cookie->getExpires(),
                $cookie->getPath(),
                $cookie->getDomain(),
                $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }

        // Send the content.
        echo $this->content;
        flush();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusLine()
    {
        return sprintf('HTTP/%s %d %s', $this->protocolVersion, $this->statusCode, $this->reasonPhrase);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return new MessageBodyString($this->content);
    }
}
