<?php

namespace Brick\DependencyInjection\Scope;

use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Definition;
use Brick\DependencyInjection\Container;

/**
 * The definition will be resolved every time it is requested.
 */
class Prototype extends Scope
{
    /**
     * {@inheritdoc}
     */
    public function get(Definition $definition, Container $container)
    {
        return $definition->resolve($container);
    }
}
