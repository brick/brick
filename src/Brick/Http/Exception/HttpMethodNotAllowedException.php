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
     * @var array
     */
    private $allowedMethods;

    /**
     * Class constructor.
     *
     * @param array $allowedMethods The allowed HTTP methods.
     */
    public function __construct(array $allowedMethods)
    {
        $this->allowedMethods = $allowedMethods;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return 405;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return ['Allow' => implode(', ', $this->allowedMethods)];
    }
}
