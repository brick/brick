<?php

namespace Brick\DependencyInjection;

use Brick\DependencyInjection\ValueResolver\ArrayValueResolver;
use Brick\Reflection\ReflectionTools;

/**
 * Instantiates classes, injects dependencies in objects and invokes functions by autowiring.
 */
class Injector
{
    /**
     * @var \Brick\DependencyInjection\InjectionPolicy
     */
    private $policy;

    /**
     * @var \Brick\DependencyInjection\ValueResolver\ArrayValueResolver
     */
    private $resolver;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param ValueResolver   $resolver
     * @param InjectionPolicy $policy
     */
    public function __construct(ValueResolver $resolver, InjectionPolicy $policy)
    {
        $this->policy = $policy;
        $this->resolver = new ArrayValueResolver($resolver);
        $this->reflectionTools = new ReflectionTools();
    }

    /**
     * Invokes a function after resolving its parameters.
     *
     * @param callable $function   The function to invoke.
     * @param array    $parameters An associative array of parameters that will take precedence over the resolver.
     *
     * @return mixed The result of the function call.
     *
     * @throws UnresolvedValueException If a function parameter could not be resolved.
     */
    public function invoke(callable $function, array $parameters = [])
    {
        $reflection = $this->reflectionTools->getReflectionFunction($function);
        $parameters = $this->getFunctionParameters($reflection, $parameters);

        return call_user_func_array($function, $parameters);
    }

    /**
     * Instantiates a class by resolving its constructor parameters, and injects dependencies in the resulting object.
     *
     * @param string $class      The name of the class to instantiate.
     * @param array  $parameters An associative array of parameters that will take precedence over the resolver.
     *
     * @return object The instantiated object.
     *
     * @throws UnresolvedValueException If a function parameter could not be resolved.
     */
    public function instantiate($class, array $parameters = [])
    {
        $class = new \ReflectionClass($class);
        $constructor = $class->getConstructor();

        $parameters = $constructor ? $this->getFunctionParameters($constructor, $parameters) : [];

        return $this->inject($class->newInstanceArgs($parameters));
    }

    /**
     * Injects dependencies in an object.
     *
     * @param object $object The object to inject dependencies in.
     *
     * @return object The object, for chaining.
     */
    public function inject($object)
    {
        $reflection = new \ReflectionObject($object);

        $this->injectMethods($reflection, $object);
        $this->injectProperties($reflection, $object);

        return $object;
    }

    /**
     * @param \ReflectionClass $class
     * @param object           $object
     *
     * @return void
     */
    private function injectMethods(\ReflectionClass $class, $object)
    {
        foreach ($this->reflectionTools->getMethods($class) as $method) {
            if ($this->policy->isMethodInjected($method)) {
                $parameters = $this->getFunctionParameters($method);
                $method->setAccessible(true);
                $method->invokeArgs($object, $parameters);
            }
        }
    }

    /**
     * @param \ReflectionClass $class
     * @param object           $object
     *
     * @return void
     */
    private function injectProperties(\ReflectionClass $class, $object)
    {
        foreach ($this->reflectionTools->getProperties($class) as $property) {
            if ($this->policy->isPropertyInjected($property)) {
                $value = $this->resolver->getPropertyValue($property);
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }
        }
    }

    /**
     * Returns an associative array of parameters to call a given function.
     *
     * The parameters are indexed by name, and returned in the same order as they are defined.
     *
     * @param \ReflectionFunctionAbstract $function   The reflection of the function.
     * @param array                       $parameters An optional array of parameters indexed by name.
     *
     * @return array The parameters to call the function with.
     *
     * @throws UnresolvedValueException If a function parameter could not be resolved.
     */
    private function getFunctionParameters(\ReflectionFunctionAbstract $function, array $parameters = [])
    {
        $result = [];

        foreach ($function->getParameters() as $parameter) {
            $name = $parameter->getName();
            $this->resolver->setValues($parameters);
            $result[$name] = $this->resolver->getParameterValue($parameter);
            $this->resolver->setValues([]);
        }

        return $result;
    }
}
