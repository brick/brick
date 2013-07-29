<?php

namespace Brick\Http\Client;

use Brick\Http\Cookie;

/**
 * A cookie with an origin, as stored by the Client.
 */
class ClientCookie
{
    /**
     * @var \Brick\Http\Cookie
     */
    private $cookie;

    /**
     * Whether the cookie is only valid on the host itself (not subdomains).
     *
     * @var boolean
     */
    private $hostOnly;

    /**
     * The domain on which the cookie is valid.
     *
     * @var string
     */
    private $domain;

    /**
     * The path on which the cookie is valid.
     *
     * @var string
     */
    private $path;

    /**
     * @var integer
     */
    private $creationTime;

    /**
     * @param \Brick\Http\Cookie $cookie
     * @param \Brick\Http\Client\CookieOrigin $origin
     */
    public function __construct(Cookie $cookie, CookieOrigin $origin)
    {
        $this->cookie = $cookie;

        $this->hostOnly = ($cookie->getDomain() === null);

        $this->domain = ($cookie->getDomain() === null ? $origin->getHost() : $cookie->getDomain());
        $this->path = ($cookie->getPath() === null ? $origin->getPath() : $cookie->getPath());

        $this->path = self::getPathDirectory($this->path);

        $this->creationTime = time();
    }

    /**
     * Returns a string that uniquely identifies a cookie by its domain, path and name.
     *
     * This allows to check cookies for equality when deciding whether a cookie replaces another.
     *
     * @return string
     */
    public function hash()
    {
        return serialize([
            $this->domain,
            $this->path,
            $this->cookie->getName()
        ]);
    }

    /**
     * Returns whether the Cookie matches the given origin.
     *
     * @param CookieOrigin $origin
     *
     * @return boolean
     */
    public function matches(CookieOrigin $origin)
    {
        if ($this->cookie->isSecure() && ! $origin->isSecure()) {
            return false;
        }

        $requestHost = explode('.', strtolower($origin->getHost()));
        $cookieDomain = explode('.', $this->domain);

        if ($this->hostOnly && $requestHost != $cookieDomain) {
            return false;
        }

        if (count($requestHost) < count($cookieDomain)) {
            return false;
        }

        if (array_slice($requestHost, -count($cookieDomain)) != $cookieDomain) {
            return false;
        }

        $path = self::getPathDirectory($origin->getPath());

        if (substr($path, 0, strlen($this->path)) != $this->path) {
            return false;
        }

        return true;
    }

    /**
     * @param string $path
     * @return string
     */
    private static function getPathDirectory($path)
    {
        return substr($path, 0, strrpos($path, '/'));
    }

    /**
     * Compares this cookie against another to determine which one should appear first in a Cookie header.
     *
     * The result is:
     *
     * * a negative number if this cookie should be returned before the other;
     * * a positive number if this cookie should be returned after the other;
     * * zero if the order does not matter.
     *
     * @param ClientCookie $that
     *
     * @return integer
     */
    public function compareTo(ClientCookie $that)
    {
        $thisLength = substr_count($this->cookie->getPath(), '/');
        $thatLength = substr_count($that->cookie->getPath(), '/');

        if ($thisLength == $thatLength) {
            // Among cookies that have equal-length path fields,
            // Cookies with earlier creation-times are listed first.
            return $this->creationTime - $that->creationTime;
        }

        // Cookies with longer paths are listed before cookies with shorter paths.
        return $thatLength - $thisLength;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->cookie->getName();
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->cookie->getValue();
    }

    /**
     * Returns whether this cookie has expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        return $this->cookie->isExpired();
    }

    /**
     * @return integer
     */
    public function getCreationtime()
    {
        return $this->creationTime;
    }

    /**
     * Returns a key=value representation of this Cookie, that can be used in a Cookie header.
     *
     * @return string
     */
    public function toString()
    {
        return $this->cookie->getName() . '=' . rawurlencode($this->cookie->getValue());
    }
}
