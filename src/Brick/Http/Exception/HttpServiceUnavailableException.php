<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the server is temporarily unable to handle the request.
 *
 * This might be due to a temporary overloading or maintenance of the server.
 */
class HttpServiceUnavailableException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return 503;
    }
}
