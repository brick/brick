<?php

namespace Brick\Http;

/**
 * Exception thrown by the RequestBody class.
 */
class RequestBodyException extends \RuntimeException
{
    /**
     * @return RequestBodyException
     */
    public static function streamPointerNotAtBeginning()
    {
        return new self('The stream pointer is not at the beginning of the stream.');
    }
}
