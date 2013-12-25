<?php

namespace Brick\DependencyInjection\Scope;

use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Definition;
use Brick\DependencyInjection\Container;

/**
 * The definition will be resolved once, then the same result will be returned every time it is requested.
 */
class Singleton extends Scope
{
    /**
     * @var boolean
     */
    private $resolved = false;

    /**
     * @var mixed
     */
    private $result;

    /**
     * {@inheritdoc}
     */
    public function get(Definition $definition, Container $container)
    {
        if (! $this->resolved) {
            $this->result   = $definition->resolve($container);
            $this->resolved = true;
        }

        return $this->result;
    }
}
