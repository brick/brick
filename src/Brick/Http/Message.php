<?php

namespace Brick\Http;

/**
 * Base class for Request and Response.
 */
abstract class Message
{
    const HTTP_1_0 = 'HTTP/1.0';
    const HTTP_1_1 = 'HTTP/1.1';

    const CRLF = "\r\n";

    /**
     * @var string
     */
    protected $protocolVersion = 'HTTP/1.0';

    /**
     * The message headers.
     *
     * The keys represent the lowercase header name, and
     * each value is an array of strings associated with the header.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * @var \Brick\Http\MessageBody|null
     */
    protected $body = null;

    /**
     * Returns the protocol version, such as 'HTTP/1.0'.
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $version
     *
     * @return static
     */
    public function setProtocolVersion($version)
    {
        $this->protocolVersion = $version;

        return $this;
    }

    /**
     * Gets all message headers.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];

        foreach ($this->headers as $name => $values) {
            $name = implode('-', array_map('ucfirst', explode('-', strtolower($name))));
            $headers[$name] = $values;
        }

        return $headers;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasHeader($name)
    {
        $name = strtolower($name);

        return isset($this->headers[$name]);
    }

    /**
     * Retrieves a header by the given case-insensitive name as a string.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeader($name)
    {
        $name = strtolower($name);

        return isset($this->headers[$name]) ? implode(', ', $this->headers[$name]) : '';
    }

    /**
     * Retrieves a header by the given case-insensitive name as an array of strings.
     *
     * @param string $name
     *
     * @return array
     */
    public function getHeaderAsArray($name)
    {
        $name = strtolower($name);

        return isset($this->headers[$name]) ? $this->headers[$name] : [];
    }

    /**
     * Sets a header, replacing any existing values of any headers with the same case-insensitive name.
     *
     * The header value MUST be a string or an array of strings.
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return static
     */
    public function setHeader($name, $value)
    {
        $name = strtolower($name);
        $this->headers[$name] = is_array($value) ? array_values($value) : [$value];

        return $this;
    }

    /**
     * Sets headers, replacing any headers that have already been set on the message.
     *
     * The array keys MUST be a string. The array values must be either a
     * string or an array of strings.
     *
     * @param array $headers
     *
     * @return static
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        return $this;
    }

    /**
     * Appends a header value to any existing values associated with the given header name.
     *
     * @param string       $name
     * @param string|array $value
     *
     * @return static
     */
    public function addHeader($name, $value)
    {
        $name = strtolower($name);

        if (is_array($value)) {
            $value = array_values($value);
            $this->headers[$name] = isset($this->headers[$name])
                ? array_merge($this->headers[$name], $value)
                : $value;
        } else {
            $this->headers[$name][] = $value;
        }

        return $this;
    }

    /**
     * Merges in an associative array of headers.
     *
     * Each array key MUST be a string representing the case-insensitive name
     * of a header. Each value MUST be either a string or an array of strings.
     * For each value, the value is appended to any existing header of the same
     * name, or, if a header does not already exist by the given name, then the
     * header is added.
     *
     * @param array $headers
     *
     * @return static
     */
    public function addHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }

        return $this;
    }

    /**
     * Removes a specific header by case-insensitive name.
     *
     * @param string $name
     *
     * @return static
     */
    public function removeHeader($name)
    {
        $name = strtolower($name);
        unset($this->headers[$name]);

        return $this;
    }

    /**
     * @return string
     */
    public function getHead()
    {
        $result = $this->getStartLine() . Message::CRLF;

        foreach ($this->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $result .= $name . ': ' . $value . Message::CRLF;
            }
        }

        return $result . Message::CRLF;
    }

    /**
     * Returns the body of the message.
     *
     * @return \Brick\Http\MessageBody|null The body, or null if not set.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the message body.
     *
     * @param \Brick\Http\MessageBody|null $body
     *
     * @return static
     */
    public function setBody(MessageBody $body = null)
    {
        $this->body = $body;

        if ($body) {
            $size = $body->getSize();

            if ($size === null) {
                $this->setHeader('Transfer-Encoding', 'chunked');
                $this->removeHeader('Content-Length');
            } else {
                $this->setHeader('Content-Length', (string) $size);
                $this->removeHeader('Transfer-Encoding');
            }
        } else {
            $this->removeHeader('Content-Length');
            $this->removeHeader('Transfer-Encoding');
        }

        return $this;
    }

    /**
     * Returns the reported Content-Length of this Message.
     *
     * If the Content-Length header is absent or invalid, this method returns zero.
     *
     * @return integer
     */
    public function getContentLength()
    {
        $contentLength = $this->getHeader('Content-Length');

        return ctype_digit($contentLength) ? (int) $contentLength : 0;
    }

    /**
     * Returns whether this message has the given Content-Type.
     *
     * The given Content-Type must consist of the type and subtype, without parameters.
     * The comparison is case-insensitive, as per RFC 1521.
     *
     * @param string $contentType The Content-Type to check, such as `text/html`.
     *
     * @return boolean
     */
    public function isContentType($contentType)
    {
        $thisContentType = $this->getHeader('Content-Type');

        $pos = strpos($thisContentType, ';');

        if ($pos !== false) {
            $thisContentType = substr($thisContentType, 0, $pos);
        }

        return strtolower($contentType) === strtolower($thisContentType);
    }

    /**
     * Returns the start line of the Request or Response.
     *
     * @return string
     */
    abstract public function getStartLine();

    /**
     * @return string
     */
    public function __toString()
    {
        $message = $this->getHead();

        if ($this->body) {
            $message .= (string) $this->body;
        }

        return $message;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        if ($this->body) {
            $this->body = clone $this->body;
        }
    }
}
