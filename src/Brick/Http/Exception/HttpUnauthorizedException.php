<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the request requires user authentication.
 */
class HttpUnauthorizedException extends HttpException
{
    /**
     * Class constructor.
     *
     * @param string          $wwwAuthenticate The contents of the WWW-Authenticate header.
     * @param string          $message         An optional exception message for debugging.
     * @param \Exception|null $previous        An optional previous exception for chaining.
     */
    public function __construct($wwwAuthenticate, $message = '', \Exception $previous = null)
    {
        $headers = [
            'WWW-Authenticate' => implode(', ', $wwwAuthenticate)
        ];

        parent::__construct(401, $headers, $message, $previous);
    }
}
