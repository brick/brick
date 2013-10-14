<?php

namespace Brick\Type;

/**
 * Associates an array of values to an object.
 */
class ObjectArrayStorage implements \IteratorAggregate
{
    /**
     * @var \SplObjectStorage
     */
    private $objects;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->objects = new \SplObjectStorage();
    }

    /**
     * @param object $object The object.
     *
     * @return array The values associated with the object.
     */
    public function get($object)
    {
        return $this->objects->offsetExists($object) ? $this->objects->offsetGet($object) : [];
    }

    /**
     * @param object $object The object.
     * @param mixed  $value  The value to associate to the object.
     *
     * @return static This ObjectArrayStorage instance.
     */
    public function add($object, $value)
    {
        $values = $this->get($object);
        $values[] = $value;
        $this->objects->offsetSet($object, $values);

        return $this;
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->objects;
    }
}
