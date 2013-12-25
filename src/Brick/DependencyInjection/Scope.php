<?php

namespace Brick\DependencyInjection;

/**
 * Defines the re-usability of a resolved type.
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
     * @param Binding   $binding
     * @param Container $container
     *
     * @return mixed
     */
    abstract public function get(Binding $binding, Container $container);
}
