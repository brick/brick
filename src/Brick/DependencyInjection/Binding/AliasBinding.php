<?php

namespace Brick\DependencyInjection\Binding;

use Brick\DependencyInjection\Binding;
use Brick\DependencyInjection\Scope;
use Brick\DependencyInjection\Container;

/**
 * Resolves a key by pointing to another.
 */
class AliasBinding extends Binding
{
    /**
     * @var string
     */
    private $target;

    /**
     * @param string $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Container $container)
    {
        return $container->get($this->target);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScope()
    {
        return Scope::prototype();
    }
}
