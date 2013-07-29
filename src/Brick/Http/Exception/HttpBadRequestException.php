<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the request could not be understood by the server due to malformed syntax.
 */
class HttpBadRequestException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return 400;
    }
}
