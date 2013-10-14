<?php

namespace Brick\DependencyInjection\Scope;

use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Binding;
use Brick\DependencyInjection\Container;

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
