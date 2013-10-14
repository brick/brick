<?php

namespace Brick\Type;

/**
 * Object-oriented representation of an associative array.
 *
 * @todo Write unit tests, in particular for path resolving.
 */
class Map implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var boolean
     */
    private $resolvePath;

    /**
     * Class constructor.
     *
     * @param array   $items An associative array.
     * @param boolean $resolvePath Whether to resolve paths such as 'a[b][c]'.
     */
    public function __construct(array $items = [], $resolvePath = false)
    {
        $this->items = $items;
        $this->resolvePath = $resolvePath;
    }

    /**
     * Returns whether the specified path exists in the map.
     *
     * @param string $path
     *
     * @return boolean
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function has($path)
    {
        try {
            $this->get($path);
        } catch (\OutOfBoundsException $e) {
            return false;
        }

        return true;
    }

    /**
     * Returns the item at the specified path.
     *
     * Example paths are:
     *  - file
     *  - files[0]
     *  - files[images][0]
     *
     * @todo remove support for files[images], replace with files.images and allow mixes: files.images[0]
     * @todo *OR* allow only files.images.0 ?
     * @todo *OR* allow both?
     * @todo *OR* [] or . defined by configuration?
     *
     * @param string $path
     *
     * @return mixed
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function get($path)
    {
        // @todo quick&dirty. Refactor this.
        if (strpos($path, '.') !== false) {
            $parts = explode('.', $path);
            $value = $this->items;
            foreach ($parts as $part) {
                if (isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    throw new \OutOfBoundsException('The specified path does not exist: ' . $path);
                }
            }

            return $value;
        }

        return $this->doGet($this->items, $path, $path);
    }

    /**
     * Recursive function internally used by get().
     *
     * @param array  $items
     * @param string $path
     * @param string $fullPath
     *
     * @return mixed
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    private function doGet(array $items, $path, $fullPath)
    {
        $pos = strpos($path, '[');
        $deep = $this->resolvePath && ($pos !== false);
        $index = $deep ? substr($path, 0, $pos) : $path;

        if (! array_key_exists($index, $items)) {
            throw new \OutOfBoundsException('The specified path does not exist: ' . $fullPath);
        }

        $item = $items[$index];

        if (! $deep) {
            return $item;
        }

        if (! is_array($item)) {
            throw new \OutOfBoundsException('The specified path does not exist: ' . $fullPath);
        }

        $path = substr($path, $pos + 1);
        $pos = strpos($path, ']');

        if ($pos === false) {
            throw new \UnexpectedValueException('Malformed path: ' . $fullPath);
        }

        $path = substr($path, 0, $pos) . substr($path, $pos + 1);

        return $this->doGet($item, $path, $fullPath);
    }

    /**
     * @param string $path
     * @return string
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \InvalidArgumentException, if the item pointed to by the path is not a string.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function getString($path)
    {
        return $this->getScalar($path, [$this, 'filterString'], 'string');
    }

    /**
     * Returns an array of strings matching the given path.
     *
     * @param string $path
     * @return string[]
     * @throws \OutOfBoundsException If the path does not exist.
     * @throws \InvalidArgumentException, if the item pointed to by the path is not an array.
     * @throws \UnexpectedValueException If the path is malformed.
     */
    public function getStrings($path)
    {
        return $this->getArray($path, [$this, 'filterString']);
    }

    /**
     * @param string $path
     * @return integer
     * @throws \OutOfBoundsException
     */
    public function getInteger($path)
    {
        return $this->getScalar($path, [$this, 'filterInteger'], 'integer');
    }

    /**
     * @param string $path
     * @return integer[]
     */
    public function getIntegers($path)
    {
        return $this->getArray($path, [$this, 'filterInteger']);
    }

    /**
     * @param string $path
     * @return float
     * @throws \OutOfBoundsException
     */
    public function getFloat($path)
    {
        return $this->getScalar($path, [$this, 'filterFloat'], 'float');
    }

    /**
     * @param string $path
     * @return float[]
     */
    public function getFloats($path)
    {
        return $this->getArray($path, [$this, 'filterFloat']);
    }

    /**
     * @param string $path
     * @return boolean
     * @throws \OutOfBoundsException
     */
    public function getBoolean($path)
    {
        return $this->getScalar($path, [$this, 'filterBoolean'], 'boolean');
    }

    /**
     * @param string $path
     * @return boolean[]
     * @throws \InvalidArgumentException
     */
    public function getBooleans($path)
    {
        return $this->getArray($path, [$this, 'filterBoolean']);
    }

    /**
     * @param mixed $value
     * @return string|null
     */
    private function filterString($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param mixed $value
     * @return integer|null
     */
    private function filterInteger($value)
    {
        $result = filter_var($value, FILTER_VALIDATE_INT);
        return ($result === false) ? null : $result;
    }

    /**
     * @param mixed $value
     * @return float|null
     */
    private function filterFloat($value)
    {
        $result = filter_var($value, FILTER_VALIDATE_FLOAT);
        return ($result === false) ? null : $result;
    }

    /**
     * @param mixed $value
     * @return boolean|null
     */
    private function filterBoolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * @param string   $path   The path of the item in the Map.
     * @param callable $filter The filter function to apply to the item.
     * @param string   $type   The expected type.
     * @return mixed
     * @throws \OutOfBoundsException
     */
    private function getScalar($path, callable $filter, $type)
    {
        $value = $this->get($path);
        $filtered = $filter($value);

        if ($filtered === null) {
            throw new \OutOfBoundsException('Cannot parse ' . gettype($value) . ' as ' . $type);
        }

        return $filtered;
    }

    /**
     * @param string   $path
     * @param callable $filter
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getArray($path, callable $filter)
    {
        try {
            $value = $this->get($path);
            if (is_array($value)) {
                return array_filter($value, $filter);
            }

            throw new \InvalidArgumentException('Expected array, got ' . gettype($value));
        } catch (\OutOfBoundsException $e) {
            // The path does not exist, hence an empty array.
            return [];
        }
    }

    /**
     * Merges new data into this Map.
     *
     * @param array $data
     * @return Map
     */
    public function add(array $data)
    {
        /**
         * @todo the merge should be recursive, but we don't want what array_merge_recursive() does.
         * This will need to be thought about when choosing what to do with the "resolve path" thing.
         */
        $this->items = array_merge($this->items, $data);

        return $this;
    }

    /**
     * @param string $path
     * @param mixed $value
     * @throws \BadMethodCallException
     */
    public function set($path, $value)
    {
        if ($this->resolvePath) {
            throw new \BadMethodCallException('Setting a value on a resolvable path Map is not yet implemented');
        }

        $this->items[$path] = $value;
    }

    /**
     * @param string $path
     * @throws \BadMethodCallException
     */
    public function remove($path)
    {
        if ($this->resolvePath) {
            throw new \BadMethodCallException('Removing a value on a resolvable path Map is not yet implemented');
        }

        unset($this->items[$path]);
    }

    /**
     * Clears the map.
     *
     * @return void
     */
    public function clear()
    {
        $this->items = [];
    }

    /**
     * Returns the map as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }
}
