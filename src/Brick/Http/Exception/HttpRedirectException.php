<?php

namespace Brick\Http\Exception;

/**
 * Exception thrown when the resource is located elsewhere.
 */
class HttpRedirectException extends HttpException
{
    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * Class constructor.
     *
     * @param string $redirectUrl
     * @param int    $statusCode
     *
     * @throws \RuntimeException
     */
    public function __construct($redirectUrl, $statusCode = 302)
    {
        if ($statusCode < 300 || $statusCode >= 400) {
            throw new \RuntimeException('Invalid HTTP redirect status code: ' . $statusCode);
        }

        $this->redirectUrl = $redirectUrl;
        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return ['Location' => $this->redirectUrl];
    }
}
