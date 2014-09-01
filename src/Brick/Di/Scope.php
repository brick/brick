<?php

namespace Brick\Di;

/**
 * Defines the re-usability of a resolved definition value.
 */
abstract class Scope
{
    /**
     * @return Scope\Singleton
     */
    public static function singleton()
    {
        return new Scope\Singleton();
    }

    /**
     * @return Scope\Prototype
     */
    public static function prototype()
    {
        return new Scope\Prototype();
    }

    /**
     * Resolves if needed, and returns a value for the given definition.
     *
     * @param Definition $definition
     * @param Container  $container
     *
     * @return mixed
     */
    abstract public function get(Definition $definition, Container $container);
}
