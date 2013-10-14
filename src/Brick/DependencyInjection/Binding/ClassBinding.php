<?php

namespace Brick\DependencyInjection\Binding;

use Brick\DependencyInjection\Binding;
use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Container;

/**
 * Resolves a class name.
 */
class ClassBinding extends Binding
{
    /**
     * The class name to instantiate.
     *
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Class constructor.
     *
     * @param string $class The class name to instantiate.
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Sets an associative array of container keys to resolve the type.
     *
     * @param array $keys
     *
     * @return ClassBinding
     */
    public function withParameters(array $keys)
    {
        $this->parameters = $keys;

        return $this;
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
        $parameters = [];

        foreach ($this->parameters as $name => $key) {
            $parameters[$name] = $container->get($key);
        }

        return $container->getInjector()->instantiate($this->class, $parameters);
    }
}
