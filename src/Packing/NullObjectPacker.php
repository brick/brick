<?php

namespace Brick\Packing;

/**
 * A null implementation of the ObjectPacker that does not target any object.
 */
class NullObjectPacker implements ObjectPacker
{
    /**
     * {@inheritdoc}
     */
    public function pack($object)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function unpack($object)
    {
        return null;
    }
}
