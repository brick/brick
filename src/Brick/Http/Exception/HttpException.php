<?php

namespace Brick\Http\Exception;

/**
 * Base class for HTTP exceptions.
 */
abstract class HttpException extends \RuntimeException
{
    /**
     * Returns the HTTP status code that corresponds to this exception.
     *
     * @return int
     */
    abstract public function getStatusCode();

    /**
     * Returns an array of HTTP headers that should be returned in the response, as key/value pairs.
     *
     * @return array
     */
    public function getHeaders()
    {
        return [];
    }
}
