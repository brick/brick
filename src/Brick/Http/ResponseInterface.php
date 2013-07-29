<?php

namespace Brick\Http;

/**
 * Interface for an immutable HTTP response.
 */
interface ResponseInterface
{
    /**
     * Returns the HTTP status code of the response.
     *
     * @return integer
     */
    public function getStatusCode();

    /**
     * Returns the HTTP protocol version of the response.
     *
     * @return string
     */
    public function getProtocolVersion();

    /**
     * Returns an associative array of the response headers.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Returns the response content as a string.
     *
     * @return string
     */
    public function getContent();

    /**
     * @return boolean
     */
    public function isInformational();

    /**
     * @return boolean
     */
    public function isSuccessful();

    /**
     * @return boolean
     */
    public function isRedirection();

    /**
     * @return boolean
     */
    public function isClientError();

    /**
     * @return boolean
     */
    public function isServerError();
}
