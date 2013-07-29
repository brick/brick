<?php

namespace Brick\Http;

use Brick\Debug;
use Brick\Type\Map;

/**
 * Represents a collection of query or POST parameters, as key-value pairs, potentially nested in sub-arrays.
 * This object is immutable.
 *
 * @todo this should not throw UploadedFileException, but a more generic exception (ParameterMapException?)
 */
class ParameterMap implements \IteratorAggregate
{
    /**
     * @var \Brick\Type\Map
     */
    private $items;

    /**
     * Class constructor.
     *
     * @param array $items An array of strings and/or such nested arrays.
     * @throws UploadedFileException If the array contains other types of variables.
     */
    public function __construct(array $items)
    {
        $this->checkItems($items);
        $this->items = new Map($items, true);
    }

    /**
     * Recursively check the items in the array.
     *
     * The array can only contain strings or other arrays.
     *
     * @param array $items
     *
     * @return void
     * @throws UploadedFileException
     */
    private function checkItems(array $items)
    {
        foreach ($items as $item) {
            if (is_array($item)) {
                $this->checkItems($item);
            } elseif (! is_string($item)) {
                throw new UploadedFileException('Expected string or array, got ' . Debug::getType($item));
            }
        }
    }

    /**
     * @param string $path
     * @return boolean
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function has($path)
    {
        return $this->items->has($path);
    }

    /**
     * @param string $path
     * @return string|array
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function get($path)
    {
        return $this->items->get($path);
    }

    /**
     * @param string $path
     * @return string
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \InvalidArgumentException, if the path is not a string.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function getString($path)
    {
        return $this->items->getString($path);
    }

    /**
     * @param string $path
     * @return string[]
     */
    public function getStrings($path)
    {
        return $this->items->getStrings($path);
    }

    /**
     * @param string $path
     * @return integer
     */
    public function getInteger($path)
    {
        return $this->items->getInteger($path);
    }

    /**
     * @param string $path
     * @return integer[]
     */
    public function getIntegers($path)
    {
        return $this->items->getIntegers($path);
    }

    /**
     * @param string $path
     * @return float
     */
    public function getFloat($path)
    {
        return $this->items->getFloat($path);
    }

    /**
     * @param string $path
     * @return float[]
     */
    public function getFloats($path)
    {
        return $this->items->getFloats($path);
    }

    /**
     * @param string $path
     * @return boolean
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \InvalidArgumentException, if the path is not recognized as a boolean value.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function getBoolean($path)
    {
        return $this->items->getBoolean($path);
    }

    /**
     * @param string $path
     * @return boolean[]
     */
    public function getBooleans($path)
    {
        return $this->items->getBooleans($path);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->items->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }
}
