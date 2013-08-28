<?php

namespace Brick\Math;

/**
 * Immutable, arbitrary-precision signed decimal numbers.
 */
class Decimal
{
    const DECIMAL_REGEXP = '/^[\-\+]?[0-9]+(?:\.([0-9]+))?$/';

    /**
     * The value of this decimal number as a string
     * (example: 1.23456)
     *
     * @var string
     */
    private $value;

    /**
     * The scale (number of digits after the decimal point)
     * of this decimal number
     *
     * @var integer
     */
    private $scale;

    /**
     * Class constructor, for internal use only.
     * Use the factory methods instead.
     *
     * @param string  $value The value, validated as a string.
     * @param integer $scale The scale, validated as an integer.
     */
    private function __construct($value, $scale = 0)
    {
        $this->value = $value;
        $this->scale = $scale;
    }

    /**
     * Creates a Decimal number representing zero.
     *
     * @return Decimal
     */
    public static function zero()
    {
        return new self('0');
    }

    /**
     * Creates a Decimal number representing one.
     *
     * @return Decimal
     */
    public static function one()
    {
        return new self('1');
    }

    /**
     * Creates a Decimal number from a string representation.
     *
     * @param string $string
     * @return Decimal
     * @throws \InvalidArgumentException
     */
    public static function fromString($string)
    {
        if (! is_string($string)) {
            $message = 'Expected string, got ' . gettype($string);
            throw new \InvalidArgumentException($message);
        }

        if (preg_match(self::DECIMAL_REGEXP, $string, $matches) == 0) {
            $message = 'String is not a valid decimal number: ' . $string;
            throw new \InvalidArgumentException($message);
        }

        $value = $matches[0];
        $scale = isset($matches[1]) ? strlen($matches[1]) : 0;

        return new self($value, $scale);
    }

    /**
     * Creates a Decimal number from an integer.
     *
     * @param integer $integer
     * @return Decimal
     * @throws \InvalidArgumentException
     */
    public static function fromInteger($integer)
    {
        if (! is_integer($integer)) {
            $message = 'Expected integer, got ' . gettype($integer);
            throw new \InvalidArgumentException($message);
        }

        return new self((string) $integer);
    }

    /**
     * Adds a Decimal number to this number.
     *
     * @param Decimal $number
     * @return Decimal
     */
    public function add(Decimal $number)
    {
        $scale = max($this->scale, $number->scale);
        $value = bcadd($this->value, $number->value, $scale);

        return new self($value, $scale);
    }

    /**
     * Subtracts a Decimal number from this number.
     *
     * @param Decimal $number
     * @return Decimal
     */
    public function subtract(Decimal $number)
    {
        $scale = max($this->scale, $number->scale);
        $value = bcsub($this->value, $number->value, $scale);

        return new self($value, $scale);
    }

    /**
     * Multiplies a Decimal number with this number.
     *
     * @param Decimal $number
     * @return Decimal
     */
    public function multiply(Decimal $number)
    {
        $scale = $this->scale + $number->scale;
        $value = bcmul($this->value, $number->value, $scale);

        return new self($value, $scale);
    }

    /**
     * @return string
     */
    private function unscaledValue()
    {
        return ltrim(str_replace('.', '', $this->toString()), '0');
    }

    /**
     * Divides a Decimal number from this number.
     *
     * @param  Decimal           $number
     * @param  integer|null      $scale
     * @param  boolean           $isExact
     * @return Decimal
     * @throws \RuntimeException
     */
    public function divide(Decimal $number, $scale = null, $isExact = true)
    {
        if ($scale === null) {
            $scale = $this->scale;
        } else {
            $this->checkScale($scale);
        }

        if ($number->isZero()) {
            throw new \RuntimeException('Division by zero');
        }

        $power = $scale - ($this->scale - $number->scale);

        $p = $this->unscaledValue();
        $q = $number->unscaledValue();

        if ($power > 0) {
            // add $power zeros to p
            $p .= str_repeat('0', $power);
        } elseif ($power < 0) {
            // add -$power zeros to q
            $q .= str_repeat('0', -$power);
        }

        $mod = bcmod($p, $q);

        if ($mod != '0') {
            if ($isExact) {
                throw new \RuntimeException('Cannot represent the exact result of the division at this scale');
            }
        }

        $result = bcdiv($this->value, $number->value, $scale);

        return new self($result, $scale);
    }

    /**
     * Returns a Decimal with the current value and the specified scale.
     *
     * @param  integer $scale
     * @param  boolean $isExact
     * @return Decimal
     */
    public function withScale($scale, $isExact = true)
    {
        return $this->divide(self::one(), $scale, $isExact);
    }

    /**
     * Returns a Decimal whose value is the absolute value of this Decimal.
     *
     * @return Decimal
     */
    public function abs()
    {
        return $this->isNegative() ? $this->negate() : $this;
    }

    /**
     * Returns the inverse of this Decimal number.
     *
     * @return Decimal
     */
    public function negate()
    {
        return self::zero()->subtract($this);
    }

    /**
     * Compares two Decimal numbers.
     *
     * @param  Decimal $number
     * @return integer [-1,0,1]
     */
    private function compareTo(Decimal $number)
    {
        $scale = max($this->scale, $number->scale);

        return bccomp($this->value, $number->value, $scale);
    }

    /**
     * Checks if this number is equal to $number.
     *
     * @param  Decimal $number
     * @return boolean
     */
    public function isEqualTo(Decimal $number)
    {
        return $this->compareTo($number) == 0;
    }

    /**
     * Checks if this number is strictly lower than $number.
     *
     * @param  Decimal $number
     * @return boolean
     */
    public function isLessThan(Decimal $number)
    {
        return $this->compareTo($number) < 0;
    }

    /**
     * Checks if this number is lower than, or equals, $number.
     *
     * @param  Decimal $number
     * @return boolean
     */
    public function isLessThanOrEqualTo(Decimal $number)
    {
        return $this->compareTo($number) <= 0;
    }

    /**
     * Checks if this number is strictly greater than $number.
     *
     * @param  Decimal $number
     * @return boolean
     */
    public function isGreaterThan(Decimal $number)
    {
        return $this->compareTo($number) > 0;
    }

    /**
     * Checks if this number is greater than, or equals, $number.
     *
     * @param  Decimal $number
     * @return boolean
     */
    public function isGreaterThanOrEqualTo(Decimal $number)
    {
        return $this->compareTo($number) >= 0;
    }

    /**
     * Checks if this number equals zero.
     *
     * @return boolean
     */
    public function isZero()
    {
        return $this->isEqualTo(self::zero());
    }

    /**
     * Checks if this number is strictly negative.
     *
     * @return boolean
     */
    public function isNegative()
    {
        return $this->isLessThan(self::zero());
    }

    /**
     * Checks if this number is negative or zero.
     *
     * @return boolean
     */
    public function isNegativeOrZero()
    {
        return $this->isLessThanOrEqualTo(self::zero());
    }

    /**
     * Checks if this number is strictly positive.
     *
     * @return boolean
     */
    public function isPositive()
    {
        return $this->isGreaterThan(self::zero());
    }

    /**
     * Checks if this number is positive or zero.
     *
     * @return boolean
     */
    public function isPositiveOrZero()
    {
        return $this->isGreaterThanOrEqualTo(self::zero());
    }

    /**
     * Returns a string representation of this number,
     * including the trailing zeros matching the scale.
     *
     * @return string
     */
    public function toString()
    {
        // ensure that the trailing zeros are returned
        return bcadd($this->value, '0', $this->scale);
    }

    /**
     * Returns a string representation of this number,
     * including the trailing zeros matching the scale.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param  mixed                     $value
     * @throws \InvalidArgumentException
     */
    private function checkInteger($value)
    {
        if (! is_integer($value)) {
            $message = 'Expected integer, got ' . gettype($value);
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @param  mixed                     $scale
     * @throws \InvalidArgumentException
     */
    private function checkScale($scale)
    {
        $this->checkInteger($scale);

        if ($scale < 0) {
            throw new \InvalidArgumentException('Scale cannot be negative');
        }
    }
}
