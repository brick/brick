<?php

namespace Brick\Di;

/**
 * Resolves the values of function parameters & class properties.
 */
interface ValueResolver
{
    /**
     * Resolves a value for a function parameter.
     *
     * @param \ReflectionParameter $parameter A reflection of the function parameter.
     *
     * @return mixed The value to pass to this parameter.
     *
     * @throws UnresolvedValueException If the value cannot be resolved.
     */
    public function getParameterValue(\ReflectionParameter $parameter);

    /**
     * Resolves a value for a class property.
     *
     * @param \ReflectionProperty $property A reflection of the class property.
     *
     * @return mixed The value to set to this property.
     *
     * @throws UnresolvedValueException If the value cannot be resolved.
     */
    public function getPropertyValue(\ReflectionProperty $property);
}
