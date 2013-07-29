<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the client is not allowed to access the resource.
 */
class HttpForbiddenException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return 403;
    }
}
