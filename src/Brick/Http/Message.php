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
    protected $protocolVersion = Message::HTTP_1_0;

    /**
     * The message headers.
     * Keys store the lowercase header name, and values arrays of Header objects.
     *
     * @var Header[][]
     */
    protected $headers = [];

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
     * Returns the headers, optionally matching the given name.
     *
     * @param string|null $name A header name, or null to return all headers.
     *
     * @return Header[]
     */
    public function getHeaders($name = null)
    {
        if ($name === null) {
            if ($this->headers) {
                return call_user_func_array('array_merge', $this->headers);
            }
        } else {
            if (isset($this->headers[$name = strtolower($name)])) {
                return $this->headers[$name];
            }
        }

        return [];
    }

    /**
     * Removes the headers, optionally matching the given name.
     *
     * @param string|null $name A header name, or null to remove all headers.
     *
     * @return static
     */
    public function removeHeaders($name = null)
    {
        if ($name === null) {
            $this->headers = [];
        } else {
            unset($this->headers[strtolower($name)]);
        }

        return $this;
    }

    /**
     * Returns whether there is at least one header with the given name.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function hasHeader($name)
    {
        return isset($this->headers[strtolower($name)]);
    }

    /**
     * Returns the value of the first header with the given name, or null if there is no such header.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getFirstHeader($name)
    {
        $headers = $this->getHeaders($name);

        return $headers ? $headers[0]->getValue() : null;
    }

    /**
     * Returns the value of the last header with the given name, or null if there is no such header.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getLastHeader($name)
    {
        $headers = $this->getHeaders($name);

        return $headers ? $headers[count($headers) - 1]->getValue() : null;
    }

    /**
     * Adds a header.
     *
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function addHeader($name, $value)
    {
        $this->headers[strtolower($name)][] = new Header($name, $value);

        return $this;
    }

    /**
     * Adds multiple headers provided as an associative array.
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
     * Sets a header, removing any previous header with the same name.
     *
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function setHeader($name, $value)
    {
        $this->removeHeaders($name);

        return $this->addHeader($name, $value);
    }

    /**
     * Sets multiple headers provided as an associative array, removing any previous headers with the same name.
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
     * @param string $name
     *
     * @return string
     */
    protected static function normalizeHeaderName($name)
    {
        return implode('-', array_map('ucfirst', explode('_', strtolower($name))));
    }

    /**
     * @return string
     */
    abstract public function getStartLine();

    /**
     * @return string
     */
    public function getHeader()
    {
        $result = $this->getStartLine() . Message::CRLF;

        foreach ($this->headers as $headers) {
            foreach ($headers as $header) {
                $result .= $header->toString() . Message::CRLF;
            }
        }

        return $result . Message::CRLF;
    }

    /**
     * @return MessageBody
     */
    abstract public function getBody();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getHeader() . $this->getBody()->toString();
    }
}
