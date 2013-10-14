<?php

namespace Brick\DependencyInjection;

class BindingHelper
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var string
     */
    private $key;

    /**
     * @param Container $container
     * @param string    $key
     */
    public function __construct(Container $container, $key)
    {
        $this->container = $container;
        $this->key       = $key;
    }

    /**
     * @return Binding\ClassBinding
     */
    public function toSelf()
    {
        return $this->set(new Binding\ClassBinding($this->key));
    }

    /**
     * @param string $class
     *
     * @return Binding\ClassBinding
     */
    public function toClass($class)
    {
        return $this->set(new Binding\ClassBinding($class));
    }

    /**
     * @param \Closure $closure
     *
     * @return Binding\ClosureBinding
     */
    public function toClosure(\Closure $closure)
    {
        return $this->set(new Binding\ClosureBinding($closure));
    }

    /**
     * Creates an alias from an entry to another.
     *
     * This generic method can be used for use cases as simple as:
     *
     *     $container->bind('my.alias')->aliasOf('my.service');
     *
     * It is particularly useful when you have already registered a class by its name,
     * but now want to make it resolvable through an interface name it implements as well:
     *
     *     $container->bind('Class\Name')->toSelf();
     *     $container->bind('Interface\Name')->aliasOf('Class\Name');
     *
     * An alias always queries the current value by default, unless you change its scope,
     * which may be used for advanced use cases, such as creating singletons out of a prototype class:
     *
     *     $container->bind('Class\Name')->toSelf()->withScope(Scope::prototype());
     *     $container->bind('my.shared.instance')->aliasOf('Class\Name')->withScope(Scope::singleton());
     *
     * @param string $key
     *
     * @return Binding\AliasBinding
     */
    public function aliasOf($key)
    {
        return $this->set(new Binding\AliasBinding($key));
    }

    /**
     * @param Binding $binding
     *
     * @return Binding
     */
    private function set(Binding $binding)
    {
        $this->container->set($this->key, $binding);

        return $binding;
    }
}
