<?php

namespace Brick\Http\Exception;

/**
 * Base class for HTTP exceptions.
 */
class HttpException extends \RuntimeException
{
    /**
     * @var array
     */
    private $headers;

    /**
     * Class constructor.
     *
     * @param string          $statusCode The HTTP status code.
     * @param array           $headers    An optional associative array of HTTP headers.
     * @param string          $message    An optional exception message for debugging.
     * @param \Exception|null $previous   An optional previous exception for chaining.
     */
    public function __construct($statusCode, array $headers = [], $message = '', \Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);

        $this->headers = $headers;
    }

    /**
     * Returns the HTTP status code that corresponds to this exception.
     *
     * This is an alias for `getCode()`.
     *
     * @return int
     */
    final public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * Returns an array of HTTP headers that should be returned in the response, as key/value pairs.
     *
     * @return array
     */
    final public function getHeaders()
    {
        return $this->headers;
    }
}
