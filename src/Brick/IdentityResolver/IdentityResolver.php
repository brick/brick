<?php

namespace Brick\IdentityResolver;

/**
 * Resolves the identity of an object.
 */
interface IdentityResolver
{
    /**
     * Returns the identity of the object, or NULL if not resolvable.
     *
     * @param object $object
     *
     * @return mixed
     */
    public function getIdentity($object);
}
