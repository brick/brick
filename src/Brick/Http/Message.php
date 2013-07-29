<?php

namespace Brick\Http;

/**
 * Base class for Request and Response.
 */
abstract class Message
{
    const HTTP_1_0 = '1.0';
    const HTTP_1_1 = '1.1';

    const CRLF = "\r\n";

    /**
     * The message headers.
     * Keys store the lowercase header name, and values arrays of Header objects.
     *
     * @var array
     */
    private $headers = [];

    /**
     * Returns all the Header objects.
     *
     * @return Header[]
     */
    public function getAllHeaders()
    {
        return count($this->headers) ? call_user_func_array('array_merge', $this->headers) : [];
    }

    /**
     * Removes all headers.
     *
     * @return static
     */
    public function removeAllHeaders()
    {
        $this->headers = [];

        return $this;
    }

    /**
     * Returns all the Header objects with the given name.
     *
     * @param string $name
     *
     * @return Header[]
     */
    public function getHeaders($name)
    {
        $name = strtolower($name);

        return isset($this->headers[$name]) ? $this->headers[$name] : [];
    }

    /**
     * Removes all headers with the given name.
     *
     * @param string $name
     *
     * @return static
     */
    public function removeHeaders($name)
    {
        unset($this->headers[strtolower($name)]);

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
        return count($this->getHeaders($name)) != 0;
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

        return count($headers) != 0 ? $headers[0]->getValue() : null;
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

        return count($headers) != 0 ? $headers[count($headers) - 1]->getValue() : null;
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
}
