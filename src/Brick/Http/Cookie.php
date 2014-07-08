<?php

namespace Brick\Http;

/**
 * An HTTP cookie.
 *
 * @todo Max-Age support.
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
    private $expires = 0;

    /**
     * The path on which the cookie is valid, or null if not set.
     *
     * @var string|null
     */
    private $path = null;

    /**
     * The domain on which the cookie is valid, or null if not set.
     *
     * @var string|null
     */
    private $domain = null;

    /**
     * Whether the cookie should only be sent on a secure connection.
     *
     * @var boolean
     */
    private $secure = false;

    /**
     * Whether the cookie should only be sent over the HTTP protocol.
     *
     * @var boolean
     */
    private $httpOnly = false;

    /**
     * Class constructor.
     *
     * @param string $name  The name of the cookie.
     * @param string $value The value of the cookie.
     */
    public function __construct($name, $value)
    {
        $this->name  = (string) $name;
        $this->value = (string) $value;
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
     * @return Cookie The cookie.
     *
     * @throws \InvalidArgumentException If the cookie string is not valid.
     */
    public static function parse($string)
    {
        $parts = preg_split('/;\s*/', $string);
        $nameValue = explode('=', array_shift($parts), 2);

        if (count($nameValue) != 2) {
            throw new \InvalidArgumentException('The cookie string is not valid.');
        }

        list ($name, $value) = $nameValue;

        if ($name === '') {
            throw new \InvalidArgumentException('The cookie string is not valid.');
        }

        if ($value === '') {
            throw new \InvalidArgumentException('The cookie string is not valid.');
        }

        $value = rawurldecode($value);
        $expires = 0;
        $path = null;
        $domain = null;
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
                                // Using @ to suppress the timezone warning, might not be the best thing to do.
                                if (is_int($time = @ strtotime($elements[1]))) {
                                    $expires = $time;
                                }
                                break;

                            case 'path':
                                    $path = $elements[1];
                                break;

                            case 'domain':
                                $domain = strtolower(ltrim($elements[1], '.'));
                        }
                    }
            }
        }

        return (new Cookie($name, $value))
            ->setExpires($expires)
            ->setPath($path)
            ->setDomain($domain)
            ->setSecure($secure)
            ->setHttpOnly($httpOnly);
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
     * Sets the cookie expiry time.
     *
     * @param integer $expires The unix timestamp at which the cookie expires, zero for a transient cookie.
     *
     * @return static This cookie.
     */
    public function setExpires($expires)
    {
        $this->expires = (int) $expires;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     *
     * @return static This cookie.
     */
    public function setPath($path)
    {
        if ($path !== null) {
            $path = (string) $path;
        }

        $this->path = $path;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string|null $domain
     *
     * @return static This cookie.
     */
    public function setDomain($domain)
    {
        if ($domain !== null) {
            $domain = (string) $domain;
        }

        $this->domain = $domain;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isHostOnly()
    {
        return $this->domain === null;
    }

    /**
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }

    /**
     * Sets whether this cookie should only be sent over a secure connection.
     *
     * @param boolean $secure True to only send over a secure connection, false otherwise.
     *
     * @return static This cookie.
     */
    public function setSecure($secure)
    {
        $this->secure = (bool) $secure;

        return $this;
    }

    /**
     * Returns whether to limit the scope of this cookie to HTTP requests.
     *
     * @return boolean True if this cookie should only be sent over a secure connection, false otherwise.
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * Sets whether to limit the scope of this cookie to HTTP requests.
     *
     * Set to true to instruct the user agent to omit the cookie when providing access to
     * cookies via "non-HTTP" APIs (such as a web browser API that exposes cookies to scripts).
     *
     * This helps mitigate the risk of client side script accessing the protected cookie
     * (provided that the user agent supports it).
     *
     * @param boolean $httpOnly
     *
     * @return static This cookie.
     */
    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = (bool) $httpOnly;

        return $this;
    }

    /**
     * Returns whether this cookie has expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        return $this->expires !== 0 && $this->expires < time();
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
        return $this->expires !== 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $cookie = $this->name . '=' . rawurlencode($this->value);

        if ($this->expires !== 0) {
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
}
