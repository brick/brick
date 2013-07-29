<?php

namespace Brick\Http;

use Brick\Type\Map;

/**
 * Represents a collection of HTTP cookies, as key-value pairs.
 * This object is immutable.
 */
class CookieMap implements \IteratorAggregate, \Countable
{
    /**
     * @var \Brick\Type\Map
     */
    private $cookies;

    /**
     * Class constructor.
     *
     * @param array $cookies The HTTP headers, as an associative array.
     * @throws \InvalidArgumentException
     */
    public function __construct(array $cookies)
    {
        $this->cookies = new Map($cookies, false);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return $this->cookies->has($name);
    }

    /**
     * @param string $name
     * @return string
     * @throws \OutOfBoundsException
     */
    public function get($name)
    {
        return $this->cookies->get($name);
    }

    /**
     * @param string $name
     * @return int
     */
    public function getInteger($name)
    {
        return $this->cookies->getInteger($name);
    }

    /**
     * @param string $name
     * @return float
     */
    public function getFloat($name)
    {
        return $this->cookies->getFloat($name);
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function getBoolean($name)
    {
        return $this->cookies->getBoolean($name);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->cookies->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->cookies->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->cookies->count();
    }
}
