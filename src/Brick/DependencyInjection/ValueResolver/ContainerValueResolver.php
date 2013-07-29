<?php

namespace Brick\DependencyInjection\ValueResolver;

use Brick\DependencyInjection\Container;
use Brick\Reflection\ReflectionTools;

/**
 * This class is internal to the dependency injection Container.
 */
class ContainerValueResolver implements ValueResolver
{
    /**
     * @var \Brick\DependencyInjection\Container
     */
    private $container;

    /**
     * @var \Brick\DependencyInjection\InjectionPolicy
     */
    private $injectionPolicy;

    /**
     * @var \Brick\DependencyInjection\ValueResolver\DefaultValueResolver
     */
    private $defaultValueResolver;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container            = $container;
        $this->injectionPolicy      = $container->getInjectionPolicy();
        $this->defaultValueResolver = new DefaultValueResolver();
        $this->reflectionTools      = new ReflectionTools();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterValue(\ReflectionParameter $parameter)
    {
        // Check if an injection key is available for this parameter.
        $key = $this->injectionPolicy->getParameterKey($parameter);
        if ($key !== null) {
            return $this->get($key);
        }

        // Try to resolve the parameter by type.
        $class = $parameter->getClass();
        if ($class) {
            $className = $class->getName();
            if ($this->container->has($className)) {
                return $this->container->get($className);
            }
        }

        return $this->defaultValueResolver->getParameterValue($parameter);
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyValue(\ReflectionProperty $property)
    {
        // Check if an injection key is available for this property.
        $key = $this->injectionPolicy->getPropertyKey($property);
        if ($key !== null) {
            return $this->get($key);
        }

        // Try to resolve the property by type.
        $className = $this->reflectionTools->getPropertyClass($property);
        if ($className !== null) {
            if ($this->container->has($className)) {
                return $this->container->get($className);
            }
        }

        return $this->defaultValueResolver->getPropertyValue($property);
    }

    /**
     * Resolves a single key, or multiple keys in an associative array.
     *
     * @param string|array $keys
     *
     * @return mixed
     */
    private function get($keys)
    {
        if (is_string($keys)) {
            return $this->container->get($keys);
        }

        foreach ($keys as $key => $value) {
            $keys[$key] = $this->container->get($value);
        }

        return $keys;
    }
}
