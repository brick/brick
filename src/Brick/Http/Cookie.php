<?php

namespace Brick\Http;

/**
 * An HTTP cookie.
 */
class Cookie
{
    /**
     * The name of the cookie.
     *
     * @var string
     */
    private $name;

    /**
     * The value of the cookie.
     *
     * @var string
     */
    private $value;

    /**
     * The unix timestamp at which the cookie expires.
     *
     * Zero if the cookie should expire at the end of the browser session.
     *
     * @var integer
     */
    private $expires;

    /**
     * The path on which the cookie is valid.
     *
     * @var string|null
     */
    private $path;

    /**
     * The domain on which the cookie is valid.
     *
     * @var string|null
     */
    private $domain;

    /**
     * Whether the cookie should only be sent on a secure connection.
     *
     * @var boolean
     */
    private $secure;

    /**
     * Whether the cookie should only be sent over the HTTP protocol.
     *
     * @var boolean
     */
    private $httpOnly;

    /**
     * @param string       $name     The name of the cookie.
     * @param string       $value    The value of the cookie.
     * @param integer      $expires  The unix timestamp when the cookie expires, or zero if browser session cookie.
     * @param string|null  $path     The path the cookie is valid on.
     * @param string|null  $domain   The domain the cookie is valid on.
     * @param boolean      $hostOnly Whether the cookie is only valid on the domain itself.
     * @param boolean      $secure   Whether the cookie should only be sent on secure connections.
     * @param boolean      $httpOnly Whether the cookie should only be sent over HTTP.
     */
    private function __construct($name, $value, $expires, $path, $domain, $hostOnly, $secure, $httpOnly)
    {
        $this->name     = $name;
        $this->value    = $value;
        $this->expires  = $expires;
        $this->path     = $path;
        $this->domain   = $domain;
        $this->hostOnly = $hostOnly;
        $this->secure   = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * @param string      $name
     * @param string      $value
     * @param integer     $expires
     * @param string|null $path
     * @param string|null $domain
     * @param boolean     $secure
     * @param boolean     $httpOnly
     *
     * @return Cookie
     */
    public static function create($name, $value, $expires = 0, $path = null, $domain = null, $secure = false, $httpOnly = false)
    {
        $hostOnly = ($domain === null);

        return new self($name, $value, $expires, $path, $domain, $hostOnly, $secure, $httpOnly);
    }

    /**
     * Creates a cookie from the contents of a Set-Cookie header.
     *
     * @param string $string
     *
     * @return Cookie|null The cookie, or null if the cookie string is not valid.
     */
    public static function parse($string)
    {
        $parts = preg_split('/;\s*/', $string);
        $nameValue = explode('=', array_shift($parts), 2);

        if (count($nameValue) != 2) {
            return null;
        }

        list ($name, $value) = $nameValue;

        $value = rawurldecode($value);
        $expires = 0;
        $path = null;
        $domain = null;
        $hostOnly = true;
        $secure = false;
        $httpOnly = false;

        foreach ($parts as $part) {
            switch (strtolower($part)) {
                case 'secure':
                    $secure = true;
                    break;

                case 'httponly':
                    $httpOnly = true;
                    break;

                default:
                    $elements = explode('=', $part, 2);
                    if (count($elements) == 2) {
                        switch (strtolower($elements[0])) {
                            case 'expires':
                                // @todo using @ to suppress the timezone warning, might not be the best thing to do
                                if (is_int($time = @ strtotime($elements[1]))) {
                                    $expires = $time;
                                }
                                break;

                            case 'path':
                                if (is_string($p = substr($elements[1], 0, strrpos($elements[1], '/')))) {
                                    $path = $p;
                                }
                                break;

                            case 'domain':
                                $domain = strtolower(ltrim($elements[1], '.'));
                                $hostOnly = false;

                            case 'max-age':
                                // @todo
                        }
                    }
            }
        }

        return new Cookie($name, $value, $expires, $path, $domain, $hostOnly, $secure, $httpOnly);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return integer
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return boolean
     */
    public function isHostOnly()
    {
        return $this->hostOnly;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * @return boolean
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * Returns whether this cookie has expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        return $this->expires != 0 && $this->expires < time();
    }

    /**
     * Returns whether the cookie is persistent.
     *
     * If false, the cookie should be discarded at the end of the session.
     * If true, the cookie should be discarded when the expiry time is reached.
     *
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->expires != 0;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $cookie = $this->name . '=' . rawurlencode($this->value);

        if ($this->expires != 0) {
            $cookie .= '; Expires=' . gmdate('r', $this->expires);
        }

        if ($this->domain !== null) {
            $cookie .= '; Domain=' . $this->domain;
        }

        if ($this->path !== null) {
            $cookie .= '; Path=' . $this->path;
        }

        if ($this->secure) {
            $cookie .= '; Secure';
        }

        if ($this->httpOnly) {
            $cookie .= '; HttpOnly';
        }

        return $cookie;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
