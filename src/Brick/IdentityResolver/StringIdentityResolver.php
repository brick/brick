<?php

namespace Brick\IdentityResolver;

/**
 * Returns the object's string representation if it has one.
 */
class StringIdentityResolver implements IdentityResolver
{
    /**
     * {@inheritdoc}
     */
    public function getIdentity($object)
    {
        if (method_exists($object, '__toString')) {
            return (string) $object;
        }

        return null;
    }
}
