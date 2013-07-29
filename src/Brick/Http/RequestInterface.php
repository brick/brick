<?php

namespace Brick\Http;

/**
 * Interface for an immutable HTTP request.
 */
interface RequestInterface
{
    /**
     * Returns the request method.
     *
     * Examples: GET, HEAD, POST, PUT, DELETE
     *
     * @return string
     */
    public function getMethod();

    /**
     * Returns whether the request method is safe (no side effects).
     *
     * The request is considered safe when the method is GET or HEAD.
     *
     * @return boolean
     */
    public function isMethodSafe();

    /**
     * Returns the scheme of the request.
     *
     * Examples: http, https
     *
     * @return string
     */
    public function getScheme();

    /**
     * Returns the host of the requested URL.
     *
     * Example: www.host.com
     *
     * @return string
     */
    public function getHost();

    /**
     * Returns the host parts of the requested URL (using dot as a separator).
     *
     * Example: ['www', 'host', 'com']
     *
     * @return array
     */
    public function getHostParts();

    /**
     * Returns the port the request targets.
     *
     * Example: 80
     *
     * @return integer
     */
    public function getPort();

    /**
     * Returns the path of the requested URL, without the query string.
     *
     * Example: /path/to/resource
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the path parts of the requested URL (using slash as a separator).
     *
     * Empty values are excluded.
     *
     * Example: ['path', 'to', 'resource']
     *
     * @return array
     */
    public function getPathParts();

    /**
     * Returns whether the requested URL has a query string.
     *
     * @return boolean
     */
    public function hasQueryString();

    /**
     * Returns the query string, or an empty string if no query string is present.
     *
     * Example: a=1&b=2
     *
     * @return string
     */
    public function getQueryString();

    /**
     * Returns the request URI, which includes the path and the query string.
     *
     * Example: /path/to/resource?a=1&b=2
     *
     * @return string
     */
    public function getRequestUri();

    /**
     * Returns the full requested URL.
     *
     * Example: http://www.host.com/path/to/resource?key=value
     *
     * @return string
     */
    public function getUrl();

    /**
     * @return array
     */
    public function getQueryParameters();

    /**
     * @return array
     */
    public function getPostParameters();

    /**
     * @return array
     */
    public function getCookies();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return array
     */
    public function getUploadedFiles();

    public function getBody();
}
