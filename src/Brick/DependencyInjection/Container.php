<?php

namespace Brick\DependencyInjection;

use Brick\DependencyInjection\InjectionPolicy\NullPolicy;
use Brick\DependencyInjection\ValueResolver\ContainerValueResolver;

/**
 * Resolves object dependencies.
 */
class Container
{
    /**
     * @var \Brick\DependencyInjection\InjectionPolicy
     */
    private $injectionPolicy;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var \Brick\DependencyInjection\Injector
     */
    private $injector;

    /**
     * @var \Brick\DependencyInjection\ValueResolver\ContainerValueResolver
     */
    private $valueResolver;

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

        $this->set(__CLASS__, $this);
        $this->set(__NAMESPACE__ . '\Injector', $this->injector);
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
     * Sets several values.
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
     * @param string $key
     *
     * @return BindingHelper
     */
    public function bind($key)
    {
        return new BindingHelper($this, $key);
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
                if ($this->injectionPolicy->isClassInjected($class)) {
                    $this->bind($key)->toSelf(); // @todo allow to configure scope (singleton) with annotations
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
     * @throws DependencyInjectionException
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

        if ($value instanceof Binding) {
            return $value->get($this);
        }

        return $value;
    }
}
