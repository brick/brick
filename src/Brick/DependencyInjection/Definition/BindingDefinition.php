<?php

namespace Brick\DependencyInjection\Definition;

use Brick\DependencyInjection\Definition;
use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Container;

/**
 * Resolves a class name.
 */
class BindingDefinition extends Definition
{
    /**
     * The class name to instantiate, or a closure to invoke.
     *
     * @var \Closure|string
     */
    private $target;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Class constructor.
     *
     * @param \Closure|string $target The class name to instantiate, or a closure to invoke.
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * @param \Closure|string $target
     *
     * @return BindingDefinition
     */
    public function to($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Sets an associative array of parameters to resolve the binding.
     *
     * @param array $parameters
     *
     * @return BindingDefinition
     */
    public function with(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Container $container)
    {
        $parameters = $container->get($this->parameters);

        if ($this->target instanceof \Closure) {
            return $container->getInjector()->invoke($this->target, $parameters);
        }

        return $container->getInjector()->instantiate($this->target, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScope()
    {
        return Scope::singleton();
    }
}
