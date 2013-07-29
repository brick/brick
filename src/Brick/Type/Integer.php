<?php

namespace Brick\Type;

use Brick\Math\Math;
use Brick\Type\Cast;

/**
 * Immutable strongly typed integer.
 */
class Integer
{
    /**
     * @var int
     */
    protected $value;

    /**
     * Class constructor.
     *
     * @param mixed $value The integer value.
     * @throws \InvalidArgumentException If the value cannot be safely converted to an integer.
     */
    public function __construct($value)
    {
        $this->value = Cast::toInteger($value);
    }

    /**
     * Returns an Integer with the given value, checking
     * to see if a new object is in fact required.
     *
     * @param int $value
     * @return \Brick\Type\Integer
     */
    protected function value($value)
    {
        if ($value == $this->value) {
            return $this;
        }

        return new Integer($value);
    }

    /**
     * @param \Brick\Type\Integer $other
     * @return \Brick\Type\Integer
     */
    public function plus(Integer $other)
    {
        return $this->value(Math::intAdd($this->value, $other->value));
    }

    /**
     * @param \Brick\Type\Integer $other
     * @return \Brick\Type\Integer
     */
    public function minus(Integer $other)
    {
        return $this->value(Math::intSub($this->value, $other->value));
    }

    /**
     * @param \Brick\Type\Integer $other
     * @return \Brick\Type\Integer
     */
    public function multipliedBy(Integer $other)
    {
        return $this->value(Math::intMul($this->value, $other->value));
    }

    /**
     * @param \Brick\Type\Integer $other
     * @return \Brick\Type\Integer
     */
    public function dividedBy(Integer $other)
    {
        // @todo what do we want here, integer result of division, or exception if not divisible?
    }
}
