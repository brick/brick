<?php

namespace Brick\Di;

use Brick\Di\Definition\AliasDefinition;
use Brick\Di\Definition\BindingDefinition;
use Brick\Di\InjectionPolicy\NullPolicy;
use Brick\Di\ValueResolver\ContainerValueResolver;
use Brick\Reflection\ReflectionTools;

/**
 * Resolves object dependencies.
 */
class Container
{
    /**
     * @var \Brick\Di\InjectionPolicy
     */
    private $injectionPolicy;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var \Brick\Di\Injector
     */
    private $injector;

    /**
     * @var \Brick\Di\ValueResolver\ContainerValueResolver
     */
    private $valueResolver;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * Class constructor.
     *
     * @param InjectionPolicy $policy
     */
    public function __construct(InjectionPolicy $policy)
    {
        $this->injectionPolicy = $policy;
        $this->valueResolver = new ContainerValueResolver($this);
        $this->injector = new Injector($this->valueResolver, $policy);
        $this->reflectionTools = new ReflectionTools();

        $this->set(self::class, $this);
        $this->set(Injector::class, $this->injector);
    }

    /**
     * Creates a simple dependency injection container.
     *
     * @return Container
     */
    public static function create()
    {
        return new Container(new NullPolicy());
    }

    /**
     * @return Injector
     */
    public function getInjector()
    {
        return $this->injector;
    }

    /**
     * @return ContainerValueResolver
     */
    public function getValueResolver()
    {
        return $this->valueResolver;
    }

    /**
     * @return InjectionPolicy
     */
    public function getInjectionPolicy()
    {
        return $this->injectionPolicy;
    }

    /**
     * Sets a single value.
     *
     * The value will be returned as is when requested with get().
     *
     * @param string $key   The key, class or interface name.
     * @param string $value The value to set.
     *
     * @return Container
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Sets multiple values.
     *
     * This is equivalent to calling set() for each key-value pair.
     *
     * @param array $values An associative array of key-value pairs.
     *
     * @return Container
     */
    public function add(array $values)
    {
        $this->items = $values + $this->items;

        return $this;
    }

    /**
     * Binds a key to a class name or a closure to be instantiated/invocated.
     *
     * By default, the key is bound to itself, so these two lines of code are equivalent;
     *
     *     $container->bind('Class\Name')
     *     $container->bind('Class\Name')->to('Class\Name')
     *
     * It can be used to bind an interface to a class to be instantiated:
     *
     *     $container->bind('Interface\Name')->to('Class\Name')
     *
     * The key can also be bound to a closure to return any value:
     *
     *     $container->bind('Class\Or\Interface\Name')->to(function() {
     *         return new Class\Name();
     *     });
     *
     * Any parameters required by the closure will be automatically resolved.
     *
     * Do not use bind() to attach an existing object instance. Use set() instead.
     *
     * @param string $key
     *
     * @return BindingDefinition
     */
    public function bind($key)
    {
        return $this->items[$key] = new BindingDefinition($key);
    }

    /**
     * Creates an alias from one key to another.
     *
     * This method can be used for use cases as simple as:
     *
     *     $container->alias('my.alias', 'my.service');
     *
     * This is particularly useful when you have already registered a class by its name,
     * but now want to make it resolvable through an interface name it implements as well:
     *
     *     $container->bind('Class\Name');
     *     $container->alias('Interface\Name', 'Class\Name');
     *
     * An alias always queries the current value by default, unless you change its scope,
     * which may be used for advanced use cases, such as creating singletons out of a prototype:
     *
     *     $container->bind('Class\Name')->in(Scope::prototype());
     *     $container->alias('my.shared.instance', 'Class\Name')->in(Scope::singleton());
     *
     * @param string $key
     * @param string $target
     *
     * @return \Brick\Di\Definition\AliasDefinition
     */
    public function alias($key, $target)
    {
        return $this->items[$key] = new AliasDefinition($target);
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        if (! isset($this->items[$key])) {
            if (class_exists($key)) {
                $class = new \ReflectionClass($key);
                $classes = $this->reflectionTools->getClassHierarchy($class);

                foreach ($classes as $class) {
                    if ($this->injectionPolicy->isClassInjected($class)) {
                        $this->bind($key); // @todo allow to configure scope (singleton) with annotations
                        break;
                    }
                }
            }
        }

        return isset($this->items[$key]);
    }

    /**
     * @param string|array $key
     *
     * @return mixed
     *
     * @throws DependencyInjectionException If the key is not registered.
     */
    public function get($key)
    {
        if (is_array($key)) {
            $result = [];

            foreach ($key as $k => $v) {
                $result[$k] = $this->get($v);
            }

            return $result;
        }

        if (! $this->has($key)) {
            throw DependencyInjectionException::keyNotRegistered($key);
        }

        $value = $this->items[$key];

        if ($value instanceof Definition) {
            return $value->get($this);
        }

        return $value;
    }
}
