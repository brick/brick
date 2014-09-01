<?php

namespace Brick\Di\Scope;

use Brick\Di\Scope;
use Brick\Di\Definition;
use Brick\Di\Container;

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
