<?php

namespace Brick\Type;

/**
 * A uniform resource locator.
 *
 * Note: this class has currently no support for user/password in the URL,
 *       and will silently drop them if such a URL passed to the constructor.
 *       This may be addressed in the future.
 */
class Url
{
    /**
     * @var array
     */
    private static $defaultPorts = [
        'http'  => 80,
        'https' => 443
    ];

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $host;

    /**
     * @var integer|null
     */
    private $port = null;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string|null
     */
    private $query = null;

    /**
     * @var string|null
     */
    private $fragment = null;

    /**
     * @param string $url
     * @throws \InvalidArgumentException
     */
    public function __construct($url)
    {
        $url = parse_url($url);

        if (! isset($url['scheme'])) {
            throw new \InvalidArgumentException('The URL has no scheme.');
        }

        $this->scheme = $url['scheme'];

        if (! isset($url['host'])) {
            throw new \InvalidArgumentException('The URL has no host.');
        }

        $this->host = $url['host'];

        if (isset($url['port'])) {
            $this->port = $url['port'];
        }

        $this->path = isset($url['path']) ? $url['path'] : '/';

        if (isset($url['query'])) {
            $this->query = $url['query'];
        }

        if (isset($url['fragment'])) {
            $this->fragment = $url['fragment'];
        }
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return boolean
     */
    public function hasPort()
    {
        return $this->port !== null;
    }

    /**
     * @return integer|null
     */
    public function getPort()
    {
        return $this->port;
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
    public function hasQuery()
    {
        return $this->query !== null;
    }

    /**
     * @return string|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return boolean
     */
    public function hasFragment()
    {
        return $this->fragment !== null;
    }

    /**
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Returns the default port for the given scheme, or null if not known.
     *
     * This is currently only supported for HTTP and HTTPS.
     *
     * @return integer|null
     */
    public function getDefaultPort()
    {
        $scheme = strtolower($this->scheme);

        return isset(self::$defaultPorts[$scheme]) ? self::$defaultPorts[$scheme] : null;
    }

    /**
     * @param Url $url
     *
     * @return boolean
     */
    public function equals(Url $url)
    {
        return $this->toString(true) == $url->toString(true);
    }

    /**
     * Returns this URL as a string, optionally normalized.
     *
     * @param boolean $normalize
     *
     * @return string
     */
    public function toString($normalize = false)
    {
        $url = $this->scheme . '://' . $this->host;

        if ($normalize) {
            $url = strtolower($url);
        }

        if ($this->port !== null) {
            if (! $normalize || $this->port !== $this->getDefaultPort()) {
                $url .= ':' . $this->port;
            }
        }

        $url .= $normalize ? $this->normalize($this->path) : $this->path;

        if ($this->query !== null) {
            $url .= '?' . ($normalize ? $this->normalize($this->query) : $this->query);
        }

        if ($this->fragment !== null) {
            $url .= '#' . $this->fragment;
        }

        return $url;
    }

    /**
     * @param string $string
     * @return string
     */
    private function normalize($string)
    {
        $callback = function (array $matches) {
            // Decode percent-encoded octets of unreserved characters.
            $char = chr(hexdec($matches[1]));

            if (preg_match('/[A–Za-z0-9\-\._~]/', $char) == 1) {
                return $char;
            }

            // Capitalize letters in escape sequences.
            return strtoupper($matches[0]);
        };

        return preg_replace_callback('/%([A-Fa-f0-9]{2})/', $callback, $string);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
