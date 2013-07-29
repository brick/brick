<?php

namespace Brick\IdentityResolver;

/**
 * Resolves the identity of an object.
 */
interface IdentityResolver
{
    /**
     * @param object $object
     *
     * @return mixed
     */
    public function getIdentity($object);
}
