<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the method is not allowed for the resource.
 *
 * As per RFC 2616:
 * The response MUST include an Allow header containing a list of valid methods for the requested resource.
 */
class HttpMethodNotAllowedException extends HttpException
{
    /**
     * Class constructor.
     *
     * @param array           $allowedMethods The allowed HTTP methods.
     * @param string          $message        An optional exception message for debugging.
     * @param \Exception|null $previous       An optional previous exception for chaining.
     */
    public function __construct(array $allowedMethods, $message = '', \Exception $previous = null)
    {
        $headers = [
            'Allow' => implode(', ', $allowedMethods)
        ];

        parent::__construct(400, $headers, $message, $previous);
    }
}
