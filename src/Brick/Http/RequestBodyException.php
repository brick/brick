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
    public static function noRequestBody()
    {
        return new self(
            'The request body is not available. ' .
            'PHP currently hides it when the Content-Type is set to multipart/form-data. ' .
            'This can also happen when it has already been read outside the Request object: ' .
            'the request body is not supposed to be read more than once.'
        );
    }

    /**
     * @return RequestBodyException
     */
    public static function requestBodyAlreadyRead()
    {
        return new self(
            'The request body has already been read as a stream. ' .
            'It cannot be read more than once.'
        );
    }

    /**
     * @return RequestBodyException
     */
    public static function streamPointerNotAtBeginning()
    {
        return new self('The stream pointer is not at the beginning of the stream.');
    }

    /**
     * @return RequestBodyException
     */
    public static function readAlreadyStarted()
    {
        return new self('Cannot get the body contents: read has already started');
    }
}
