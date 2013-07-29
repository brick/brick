<?php

namespace Brick\Http\Client;

use Brick\Http\Request;

/**
 * Encapsulates details of an origin server that
 * are relevant when parsing, validating or matching HTTP cookies.
 */
class CookieOrigin
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $path;

    /**
     * @var boolean
     */
    private $secure;

    /**
     * @param string  $host
     * @param string  $path
     * @param boolean $secure
     */
    public function __construct($host, $path, $secure)
    {
        $this->host   = $host;
        $this->path   = $path;
        $this->secure = $secure;
    }

    /**
     * Creates a CookieOrigin from a Request instance.
     *
     * @param Request $request
     *
     * @return CookieOrigin
     */
    public static function createFromRequest(Request $request)
    {
        return new self($request->getHost(), $request->getPath(), $request->isSecure());
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }
}
