<?php

namespace Brick\Math;

use Brick\Math\Calculator;

/**
 * An arbitrary-size integer.
 *
 * All methods accepting a number as a parameter accept either a BigInteger instance,
 * an integer, or a string representing an arbitrary size integer.
 */
class BigInteger
{
    /**
     * @var string
     */
    private $value;

    /**
     * Private constructor. Use the factory methods.
     *
     * @param string $value A string of digits, with optional leading minus sign.
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the minimum of the given values.
     *
     * @param array<BigInteger|integer|string> An array of integers to return the minimum value of.
     *
     * @return \Brick\Math\BigInteger The minimum value.
     *
     * @throws \InvalidArgumentException If no values are given, or an invalid value is given.
     */
    public static function min(array $values)
    {
        $min = null;

        foreach ($values as $value) {
            $value = BigInteger::of($value);
            if ($min === null || $value->isLessThan($min)) {
                $min = $value;
            }
        }

        if ($min === null) {
            throw new \InvalidArgumentException(__METHOD__ . '() expects at least one value.');
        }

        return $min;
    }

    /**
     * Returns the maximum of the given values.
     *
     * @param array<BigInteger|integer|string> An array of integers to return the maximum value of.
     *
     * @return \Brick\Math\BigInteger The maximum value.
     *
     * @throws \InvalidArgumentException If no values are given, or an invalid value is given.
     */
    public static function max(array $values)
    {
        $max = null;

        foreach ($values as $value) {
            $value = BigInteger::of($value);
            if ($max === null || $value->isGreaterThan($max)) {
                $max = $value;
            }
        }

        if ($max === null) {
            throw new \InvalidArgumentException(__METHOD__ . '() expects at least one value.');
        }

        return $max;
    }

    /**
     * Returns a BigInteger of the given value.
     *
     * @param BigInteger|integer|string $value
     *
     * @return BigInteger
     *
     * @throws \InvalidArgumentException If the number is malformed.
     */
    public static function of($value)
    {
        if ($value instanceof BigInteger) {
            return $value;
        }

        if (is_int($value)) {
            return new BigInteger((string) $value);
        }

        $value = (string) $value;

        if (preg_match('/^\-?[0-9]+?$/', $value, $matches) == 0) {
            throw new \InvalidArgumentException(sprintf('%s does not represent a valid number.', $value));
        }

        return new BigInteger($value);
    }

    /**
     * Returns a BigInteger representing zero.
     *
     * This value is cached to optimize memory consumption as it is frequently used.
     *
     * @return BigInteger
     */
    public static function zero()
    {
        static $zero = null;

        if ($zero === null) {
            $zero = new BigInteger('0');
        }

        return $zero;
    }

    /**
     * Returns the sum of this number and the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return BigInteger
     */
    public function plus($that)
    {
        $that = BigInteger::of($that);
        $value = Calculator::get()->add($this->value, $that->value);

        return new BigInteger($value);
    }

    /**
     * Returns the difference of this number and the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return BigInteger
     */
    public function minus($that)
    {
        $that = BigInteger::of($that);
        $value = Calculator::get()->sub($this->value, $that->value);

        return new BigInteger($value);
    }

    /**
     * Returns the result of the multiplication of this number and the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return BigInteger
     */
    public function multipliedBy($that)
    {
        $that = BigInteger::of($that);
        $value = Calculator::get()->mul($this->value, $that->value);

        return new BigInteger($value);
    }

    /**
     * Returns the result of the division of this number and the given one.
     *
     * @param BigInteger|integer|string $that
     * @param integer                   $roundingMode
     *
     * @return BigInteger
     */
    public function dividedBy($that, $roundingMode = RoundingMode::UNNECESSARY)
    {
        $that = BigInteger::of($that);

        if ($that->isZero()) {
            throw ArithmeticException::divisionByZero();
        }

        $p = $this->value;
        $q = $that->value;

        $calculator = Calculator::get();
        $result = $calculator->div($p, $q, $remainder);

        $hasDiscardedFraction = ($remainder !== '0');
        $isPositiveOrZero = ($p[0] === '-') === ($q[0] === '-');

        $discardedFractionSign = function() use ($calculator, $remainder, $q) {
            $r = $calculator->abs($calculator->mul($remainder, '2'));
            $q = $calculator->abs($q);

            return $calculator->cmp($r, $q);
        };

        $increment = false;

        switch ($roundingMode) {
            case RoundingMode::UNNECESSARY:
                if ($hasDiscardedFraction) {
                    throw ArithmeticException::roundingNecessary();
                }
                break;

            case RoundingMode::UP:
                $increment = $hasDiscardedFraction;
                break;

            case RoundingMode::DOWN:
                break;

            case RoundingMode::CEILING:
                $increment = $hasDiscardedFraction && $isPositiveOrZero;
                break;

            case RoundingMode::FLOOR:
                $increment = $hasDiscardedFraction && ! $isPositiveOrZero;
                break;

            case RoundingMode::HALF_UP:
                $increment = $discardedFractionSign() >= 0;
                break;

            case RoundingMode::HALF_DOWN:
                $increment = $discardedFractionSign() > 0;
                break;

            case RoundingMode::HALF_CEILING:
                $increment = $isPositiveOrZero ? $discardedFractionSign() >= 0 : $discardedFractionSign() > 0;
                break;

            case RoundingMode::HALF_FLOOR:
                $increment = $isPositiveOrZero ? $discardedFractionSign() > 0 : $discardedFractionSign() >= 0;
                break;

            case RoundingMode::HALF_EVEN:
                $lastDigit = (int) substr($result, -1);
                $lastDigitIsEven = ($lastDigit % 2 === 0);
                $increment = $lastDigitIsEven ? $discardedFractionSign() > 0 : $discardedFractionSign() >= 0;
                break;

            default:
                throw new \InvalidArgumentException('Invalid rounding mode.');
        }

        if ($increment) {
            $result = $isPositiveOrZero
                ? $calculator->add($result, '1')
                : $calculator->sub($result, '1');
        }

        return new BigInteger($result);
    }

    /**
     * Returns the absolute value of this number.
     *
     * @return \Brick\Math\BigInteger
     */
    public function abs()
    {
        return $this->isNegative() ? $this->negated() : $this;
    }

    /**
     * Returns the inverse of this number.
     *
     * @return \Brick\Math\BigInteger
     */
    public function negated()
    {
        return new BigInteger(Calculator::get()->neg($this->value));
    }

    /**
     * Returns a BigInteger whose value is shifted left by the given number of bits (this << bits).
     *
     * @param integer $bits
     *
     * @return \Brick\Math\BigInteger
     *
     * @throws \InvalidArgumentException If `$bits` is negative.
     */
    public function shiftedLeft($bits)
    {
        $bits = (int) $bits;

        if ($bits === 0) {
            return $this;
        }

        if ($bits < 0) {
            throw new \InvalidArgumentException('The number of bits to shift must not be negative.');
        }

        return $this->multipliedBy(Calculator::get()->pow('2', $bits));
    }

    /**
     * Returns a BigInteger whose value is shifted right by the given number of bits (this >> bits).
     *
     * @param integer $bits
     *
     * @return \Brick\Math\BigInteger
     *
     * @throws \InvalidArgumentException If `$bits` is negative.
     */
    public function shiftedRight($bits)
    {
        $bits = (int) $bits;

        if ($bits === 0) {
            return $this;
        }

        if ($bits < 0) {
            throw new \InvalidArgumentException('The number of bits to shift must not be negative.');
        }

        return $this->dividedBy(Calculator::get()->pow('2', $bits), RoundingMode::FLOOR);
    }

    /**
     * Compares this number to the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return integer [-1,0,1]
     */
    public function compareTo($that)
    {
        $that = BigInteger::of($that);

        return Calculator::get()->cmp($this->value, $that->value);
    }

    /**
     * Checks if this number is equal to the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return boolean
     */
    public function isEqualTo($that)
    {
        return $this->compareTo($that) === 0;
    }

    /**
     * Checks if this number is strictly lower than the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return boolean
     */
    public function isLessThan($that)
    {
        return $this->compareTo($that) < 0;
    }

    /**
     * Checks if this number is lower than or equal to the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return boolean
     */
    public function isLessThanOrEqualTo($that)
    {
        return $this->compareTo($that) <= 0;
    }

    /**
     * Checks if this number is strictly greater than the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return boolean
     */
    public function isGreaterThan($that)
    {
        return $this->compareTo($that) > 0;
    }

    /**
     * Checks if this number is greater than or equal to the given one.
     *
     * @param BigInteger|integer|string $that
     *
     * @return boolean
     */
    public function isGreaterThanOrEqualTo($that)
    {
        return $this->compareTo($that) >= 0;
    }

    /**
     * Checks if this number equals zero.
     *
     * @return boolean
     */
    public function isZero()
    {
        return ($this->value === '0');
    }

    /**
     * Checks if this number is strictly negative.
     *
     * @return boolean
     */
    public function isNegative()
    {
        return ($this->value[0] === '-');
    }

    /**
     * Checks if this number is negative or zero.
     *
     * @return boolean
     */
    public function isNegativeOrZero()
    {
        return ($this->value === '0') || ($this->value[0] === '-');
    }

    /**
     * Checks if this number is strictly positive.
     *
     * @return boolean
     */
    public function isPositive()
    {
        return ($this->value !== '0') && ($this->value[0] !== '-');
    }

    /**
     * Checks if this number is positive or zero.
     *
     * @return boolean
     */
    public function isPositiveOrZero()
    {
        return ($this->value[0] !== '-');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
