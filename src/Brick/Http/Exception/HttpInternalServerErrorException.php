<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when an internal server error has occurred.
 * This is usually an error that is not the result of a problem with the request.
 */
class HttpInternalServerErrorException extends HttpException
{
    /**
     * Class constructor.
     *
     * @param string          $message  An optional exception message for debugging.
     * @param \Exception|null $previous An optional previous exception for chaining.
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(500, [], $message, $previous);
    }
}
