<?php

namespace Brick\DependencyInjection;

/**
 * Resolves a key by pointing to another.
 */
class AliasBinding extends Binding
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScope()
    {
        return Scope::prototype();
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Container $container)
    {
        return $container->get($this->key);
    }
}
