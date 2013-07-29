<?php

namespace Brick\DependencyInjection;

/**
 * The binding will be resolved once, then the same result will be served every time it is requested.
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
