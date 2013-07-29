<?php

namespace Brick\Packing;

use Brick\Packing\ObjectPacker;
use Brick\Reflection\ReflectionTools;

/**
 * Packs a variable for serialization.
 */
class Packer
{
    /**
     * @var \Brick\Packing\ObjectPacker
     */
    private $objectPacker;

    /**
     * @var \Brick\Reflection\ReflectionTools
     */
    private $reflectionTools;

    /**
     * @param ObjectPacker $objectPacker
     */
    public function __construct(ObjectPacker $objectPacker)
    {
        $this->objectPacker = $objectPacker;
        $this->reflectionTools = new ReflectionTools();
    }

    /**
     * Returns a copy of the given variable, with all packable objects packed.
     *
     * @param mixed $variable
     * @return mixed
     */
    public function pack($variable)
    {
        return $this->copy($variable, true);
    }

    /**
     * Returns a copy of the given variable, with all packable objects unpacked.
     *
     * @param mixed $variable
     * @return mixed
     */
    public function unpack($variable)
    {
        return $this->copy($variable, false);
    }

    public function serialize($variable)
    {
        return serialize($this->pack($variable));
    }

    public function unserialize($variable)
    {
        return $this->unpack(unserialize($variable));
    }

    /**
     * @param mixed   $variable The variable to copy.
     * @param boolean $pack     True to pack, false to unpack.
     * @param array   $visited  The visited objects, for recursive calls.
     * @param int     $level    The nesting level.
     *
     * @return mixed
     */
    private function copy($variable, $pack, array & $visited = [], $level = 0)
    {
        if (is_object($variable)) {
            $hash = spl_object_hash($variable);

            if (isset($visited[$hash])) {
                return $visited[$hash];
            }

            $processed = $pack
                ? $this->objectPacker->pack($variable)
                : $this->objectPacker->unpack($variable);

            if ($processed) {
                return $visited[$hash] = $processed;
            }

            $class = new \ReflectionClass($variable);
            $properties = $this->reflectionTools->getProperties($class);

            if (! $class->isUserDefined()) {
                if ($class->isCloneable()) {
                    return $visited[$hash] = clone $variable;
                } else {
                    return $visited[$hash] = $variable;
                }
            }

            $visited[$hash] = $copy = $class->newInstanceWithoutConstructor();

            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($variable);
                $processed = $this->copy($value, $pack, $visited, $level + 1);
                $property->setValue($copy, $processed);
            }

            return $copy;
        }

        if (is_array($variable)) {
            foreach ($variable as & $value) {
                $value = $this->copy($value, $pack, $visited, $level + 1);
            }
        }

        return $variable;
    }
}
