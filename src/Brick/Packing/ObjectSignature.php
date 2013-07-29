<?php

namespace Brick\Packing;

/**
 * The exact information needed to retrieve an object from a data store.
 */
class ObjectSignature
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var mixed
     */
    private $identity;

    /**
     * @param string $class
     * @param mixed  $identity
     */
    public function __construct($class, $identity)
    {
        $this->class = $class;
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
