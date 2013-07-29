<?php

namespace Brick\Packing;

/**
 * Packs and unpacks objects for serialization.
 */
interface ObjectPacker
{
    /**
     * Packs an object for serialization.
     *
     * @param object $object The object to pack.
     *
     * @return object|null The packed object, or null if it's not targeted by this packer.
     */
    public function pack($object);

    /**
     * Unpacks an object after deserialization.
     *
     * @param object $object The object to unpack.
     *
     * @return object|null The unpacked object, or null if it's not targeted by this packer.
     */
    public function unpack($object);
}
