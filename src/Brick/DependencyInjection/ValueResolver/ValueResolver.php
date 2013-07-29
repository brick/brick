<?php

namespace Brick\DependencyInjection\ValueResolver;

/**
 * Resolves the values of function parameters & class properties.
 * @todo If we're going to keep this in DependencyInjection, move at the same level as InjectionPolicy.
 * @todo But what about UnresolvedValueException?
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
