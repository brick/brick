<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the resource cannot be found.
 */
class HttpNotFoundException extends HttpException
{
    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return 404;
    }
}
