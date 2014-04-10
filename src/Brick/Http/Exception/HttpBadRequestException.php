<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the request could not be understood by the server due to malformed syntax.
 */
class HttpBadRequestException extends HttpException
{
    /**
     * Class constructor.
     *
     * @param string          $message  An optional exception message for debugging.
     * @param \Exception|null $previous An optional previous exception for chaining.
     */
    public function __construct($message = '', \Exception $previous = null)
    {
        parent::__construct(400, [], $message, $previous);
    }
}
