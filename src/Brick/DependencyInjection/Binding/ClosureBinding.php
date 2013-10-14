<?php

namespace Brick\DependencyInjection\Binding;

use Brick\DependencyInjection\Binding;
use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Container;

/**
 * Resolves a key with a closure.
 */
class ClosureBinding extends Binding
{
    /**
     * The closure used to resolve the value.
     *
     * @var \Closure
     */
    private $closure;

    /**
     * @param \Closure $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScope()
    {
        return Scope::singleton();
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Container $container)
    {
        return $container->getInjector()->invoke($this->closure);
    }
}
