<?php

namespace Brick\DependencyInjection;

/**
 * Exception thrown by the dependency injection classes.
 */
class DependencyInjectionException extends \RuntimeException
{
    /**
     * @param string $key
     *
     * @return DependencyInjectionException
     */
    public static function keyNotRegistered($key)
    {
        return new self('Key not registered: ' . $key);
    }
}
