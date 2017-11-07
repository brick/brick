<?php

declare(strict_types=1);

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
     * @var int|null
     */
    private $port;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string|null
     */
    private $query;

    /**
     * @var string|null
     */
    private $fragment;

    /**
     * @param string $url
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $url)
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
    public function getScheme() : string
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * @return bool
     */
    public function hasPort() : bool
    {
        return $this->port !== null;
    }

    /**
     * @return int|null
     */
    public function getPort() : ?int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath() : string
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function hasQuery() : bool
    {
        return $this->query !== null;
    }

    /**
     * @return string|null
     */
    public function getQuery() : ?string
    {
        return $this->query;
    }

    /**
     * @return bool
     */
    public function hasFragment() : bool
    {
        return $this->fragment !== null;
    }

    /**
     * @return string|null
     */
    public function getFragment() : ?string
    {
        return $this->fragment;
    }

    /**
     * Returns the default port for the given scheme, or null if not known.
     *
     * This is currently only supported for HTTP and HTTPS.
     *
     * @return int|null
     */
    public function getDefaultPort() : ?int
    {
        $scheme = strtolower($this->scheme);

        return isset(self::$defaultPorts[$scheme]) ? self::$defaultPorts[$scheme] : null;
    }

    /**
     * @param Url $url
     *
     * @return bool
     */
    public function equals(Url $url) : bool
    {
        return $this->toString(true) == $url->toString(true);
    }

    /**
     * Returns this URL as a string, optionally normalized.
     *
     * @param bool $normalize
     *
     * @return string
     */
    public function toString(bool $normalize = false) : string
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
     *
     * @return string
     */
    private function normalize(string $string) : string
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
    public function __toString() : string
    {
        return $this->toString();
    }
}
