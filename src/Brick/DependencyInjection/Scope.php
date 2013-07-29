<?php

namespace Brick\DependencyInjection;

/**
 * Defines the reusability of a result.
 */
abstract class Scope
{
    /**
     * @return Scope
     */
    public static function singleton()
    {
        return new Singleton();
    }

    /**
     * @return Scope
     */
    public static function prototype()
    {
        return new Prototype();
    }

    /**
     * @param Binding   $binding
     * @param Container $container
     *
     * @return mixed
     */
    abstract public function get(Binding $binding, Container $container);
}
