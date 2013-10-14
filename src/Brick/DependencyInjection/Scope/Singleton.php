<?php

namespace Brick\DependencyInjection\Scope;

use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Binding;
use Brick\DependencyInjection\Container;

/**
 * The binding will be resolved once, then the same result will be returned every time it is requested.
 */
class Singleton extends Scope
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * {@inheritdoc}
     */
    public function get(Binding $binding, Container $container)
    {
        if ($this->result) {
            return $this->result;
        }

        return $this->result = $binding->resolve($container);
    }
}
