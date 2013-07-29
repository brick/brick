<?php

namespace Brick\DependencyInjection;

/**
 * The binding will be resolved every time it is requested.
 */
class Prototype extends Scope
{
    /**
     * {@inheritdoc}
     */
    public function get(Binding $binding, Container $container)
    {
        return $binding->resolve($container);
    }
}
