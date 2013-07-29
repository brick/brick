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
     * @return ClassBinding
     */
    public function toSelf()
    {
        return $this->set(new ClassBinding($this->key));
    }

    /**
     * @param string $class
     *
     * @return ClassBinding
     */
    public function toClass($class)
    {
        return $this->set(new ClassBinding($class));
    }

    /**
     * @param \Closure $closure
     *
     * @return ClosureBinding
     */
    public function toClosure(\Closure $closure)
    {
        return $this->set(new ClosureBinding($closure));
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
     * @return AliasBinding
     */
    public function aliasOf($key)
    {
        return $this->set(new AliasBinding($key));
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
