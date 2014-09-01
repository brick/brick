<?php

namespace Brick\Di\Definition;

use Brick\Di\Definition;
use Brick\Di\Scope;
use Brick\Di\Container;

/**
 * Resolves a key by pointing to another.
 */
class AliasDefinition extends Definition
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
