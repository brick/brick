<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when an internal server error has occurred.
 * This is usually an error that is not the result of a problem with the request.
 */
class HttpInternalServerErrorException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return 500;
    }
}
