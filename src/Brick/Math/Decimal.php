<?php

namespace Brick\Math;

use Brick\Type\Cast;

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
     *
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
     * Returns a decimal number representing zero.
     *
     * @return Decimal
     */
    public static function zero()
    {
        static $zero;

        return $zero ?: $zero = new Decimal('0');
    }

    /**
     * Returns a decimal number representing one.
     *
     * @return Decimal
     */
    public static function one()
    {
        static $one;

        return $one ?: $one = new Decimal('1');
    }

    /**
     * Returns the minimum of the given values.
     *
     * @param array<Decimal|number|string> An array of decimals to return the minimum value of.
     *
     * @return Decimal The minimum value.
     *
     * @throws \InvalidArgumentException If no values are given, or an invalid value is given.
     */
    public static function min(array $values)
    {
        $min = null;

        foreach ($values as $value) {
            $value = Decimal::of($value);
            if ($min === null || $value->isLessThan($min)) {
                $min = $value;
            }
        }

        if ($min === null) {
            throw new \InvalidArgumentException('Decimal::min() expects at least one value.');
        }

        return $min;
    }

    /**
     * Returns the maximum of the given values.
     *
     * @param array<Decimal|number|string> An array of decimals to return the maximum value of.
     *
     * @return Decimal The maximum value.
     *
     * @throws \InvalidArgumentException If no values are given, or an invalid value is given.
     */
    public static function max(array $values)
    {
        $max = null;

        foreach ($values as $value) {
            $value = Decimal::of($value);
            if ($max === null || $value->isGreaterThan($max)) {
                $max = $value;
            }
        }

        if ($max === null) {
            throw new \InvalidArgumentException('Decimal::max() expects at least one value.');
        }

        return $max;
    }

    /**
     * Returns a decimal of the given value.
     *
     * Note: you should avoid passing floating point numbers to this method:
     * being imprecise by design, they might not convert to the decimal value you expect.
     * Prefer passing decimal numbers as strings, e.g `Decimal::of('0.1')` over `Decimal::of(0.1)`.
     *
     * @param Decimal|number|string $value
     *
     * @return Decimal
     *
     * @throws \InvalidArgumentException If the type is not supported or the number is malformed.
     */
    public static function of($value)
    {
        if ($value instanceof Decimal) {
            return $value;
        }

        if (is_int($value)) {
            return new Decimal((string) $value);
        }

        $value = Cast::toString($value);

        if (preg_match(Decimal::DECIMAL_REGEXP, $value, $matches) == 0) {
            $message = 'String is not a valid decimal number: ' . $value;
            throw new \InvalidArgumentException($message);
        }

        $value = $matches[0];
        $scale = isset($matches[1]) ? strlen($matches[1]) : 0;

        return new Decimal($value, $scale);
    }

    /**
     * Returns the sum of this number and that one.
     *
     * @param Decimal|number|string $that
     *
     * @return Decimal
     */
    public function plus($that)
    {
        $that = Decimal::of($that);

        $scale = max($this->scale, $that->scale);
        $value = bcadd($this->value, $that->value, $scale);

        return new Decimal($value, $scale);
    }

    /**
     * Returns the difference of this number and that one.
     *
     * @param Decimal|number|string $that
     *
     * @return Decimal
     */
    public function minus($that)
    {
        $that = Decimal::of($that);

        $scale = max($this->scale, $that->scale);
        $value = bcsub($this->value, $that->value, $scale);

        return new Decimal($value, $scale);
    }

    /**
     * Returns the result of the multiplication of this number and that one.
     *
     * @param Decimal|number|string $that
     *
     * @return Decimal
     */
    public function multipliedBy($that)
    {
        $that = Decimal::of($that);

        $scale = $this->scale + $that->scale;
        $value = bcmul($this->value, $that->value, $scale);

        return new Decimal($value, $scale);
    }

    /**
     * @return string
     */
    private function unscaledValue()
    {
        $value = $this->toString();

        $prefix = ($value[0] == '-') ? '-' : '';

        if ($prefix == '-') {
            $value = substr($value, 1);
        }

        $value = str_replace('.', '', $value);
        $value = ltrim($value, '0');

        return $prefix . $value;
    }

    /**
     * Returns the result of the division of this number and that one.
     *
     * @param Decimal|number|string $that         The number to divide.
     * @param integer|null          $scale        The scale, or null to use the scale of this number.
     * @param integer               $roundingMode The rounding mode.
     *
     * @return Decimal
     *
     * @throws ArithmeticException
     * @throws \InvalidArgumentException
     */
    public function dividedBy($that, $scale = null, $roundingMode = RoundingMode::UNNECESSARY)
    {
        $that = Decimal::of($that);

        if ($that->isZero()) {
            throw ArithmeticException::divisionByZero();
        }

        if ($scale === null) {
            $scale = $this->scale;
        } else {
            $scale = (int) $scale;

            if ($scale < 0) {
                throw new \InvalidArgumentException('Scale cannot be negative.');
            }
        }

        $power = $scale - ($this->scale - $that->scale);

        $p = $this->unscaledValue();
        $q = $that->unscaledValue();

        if ($power > 0) {
            // add $power zeros to p
            $p .= str_repeat('0', $power);
        } elseif ($power < 0) {
            // add -$power zeros to q
            $q .= str_repeat('0', -$power);
        }

        if ($q[0] == '-') {
            $q = substr($q, 1);
        }

        $mod = bcmod($p, $q);
        $modSign = bccomp($mod, '0', 0);
        $hasDiscardedFraction = ($modSign != 0);

        $isPositiveOrZero = $modSign >= 0;

        if ($modSign < 0) {
            $mod = substr($mod, 1);
        }

        $cmp = bccomp(bcmul($mod, '2', 0), $q, 0);
        $isDiscardedFractionHalfOrMore   = $cmp >= 0;
        $isDiscardedFractionMoreThanHalf = $cmp > 0;

        $result = bcdiv($this->value, $that->value, $scale);

        $increment = false;

        switch ($roundingMode) {
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
                $increment = $isDiscardedFractionHalfOrMore;
                break;

            case RoundingMode::HALF_DOWN:
                $increment = $isDiscardedFractionMoreThanHalf;
                break;

            case RoundingMode::HALF_CEILING:
                $increment = $isPositiveOrZero ? $isDiscardedFractionHalfOrMore : $isDiscardedFractionMoreThanHalf;
                break;

            case RoundingMode::HALF_FLOOR:
                $increment = $isPositiveOrZero ? $isDiscardedFractionMoreThanHalf : $isDiscardedFractionHalfOrMore;
                break;

            case RoundingMode::HALF_EVEN:
                $lastDigit = substr($result, -1);
                $lastDigitIsEven = ($lastDigit % 2 == 0);
                $increment = ($lastDigitIsEven ? $isDiscardedFractionMoreThanHalf : $isDiscardedFractionHalfOrMore);
                break;

            case RoundingMode::UNNECESSARY:
                if ($hasDiscardedFraction) {
                    throw ArithmeticException::roundingNecessary();
                }
                break;

            default:
                throw new \InvalidArgumentException('Invalid rounding mode.');
        }

        if ($increment) {
            $unit = bcpow('10', -$scale, $scale);
            if (! $isPositiveOrZero) {
                $unit = '-' . $unit;
            }
            $result = bcadd($result, $unit, $scale);
        }

        return new Decimal($result, $scale);
    }

    /**
     * Returns a Decimal with the current value and the specified scale.
     *
     * @param integer $scale
     * @param integer $roundingMode
     *
     * @return Decimal
     */
    public function withScale($scale, $roundingMode = RoundingMode::UNNECESSARY)
    {
        if ($scale == $this->scale) {
            return $this;
        }

        return $this->dividedBy(Decimal::one(), $scale, $roundingMode);
    }

    /**
     * Returns a Decimal whose value is the absolute value of this Decimal.
     *
     * @return Decimal
     */
    public function abs()
    {
        return $this->isNegative() ? $this->negated() : $this;
    }

    /**
     * Returns a decimal representing the inverse of this decimal.
     *
     * @return Decimal
     */
    public function negated()
    {
        return Decimal::zero()->minus($this);
    }

    /**
     * @param Decimal|number|string $that
     *
     * @return Decimal
     *
     * @throws ArithmeticException If the argument is zero.
     */
    public function mod($that)
    {
        $that = Decimal::of($that);

        if ($that->isZero()) {
            throw ArithmeticException::divisionByZero();
        }

        $power = $that->scale - $this->scale;

        $p = $this->unscaledValue();
        $q = $that->unscaledValue();

        if ($power > 0) {
            // add $power zeros to p
            $p .= str_repeat('0', $power);
        } elseif ($power < 0) {
            // add -$power zeros to q
            $q .= str_repeat('0', -$power);
        }

        $mod = bcmod($p, $q);
        $max = max($this->scale, $that->scale);

        $multiplicand = bcpow(10, -$max, $max);
        $result = bcmul($mod, $multiplicand, $max);

        return new Decimal($result, $max);
    }

    /**
     * Compares this number with the given one.
     *
     * @param Decimal|number|string $that
     *
     * @return integer [-1,0,1]
     */
    public function compareTo($that)
    {
        $that = Decimal::of($that);

        return bccomp($this->value, $that->value, max($this->scale, $that->scale));
    }

    /**
     * Checks if this number is equal to that number.
     *
     * @param Decimal|number|string $that
     *
     * @return boolean
     */
    public function isEqualTo($that)
    {
        $that = Decimal::of($that);

        return $this->compareTo($that) == 0;
    }

    /**
     * Checks if this number is strictly lower than that number.
     *
     * @param Decimal|number|string $that
     *
     * @return boolean
     */
    public function isLessThan($that)
    {
        $that = Decimal::of($that);

        return $this->compareTo($that) < 0;
    }

    /**
     * Checks if this number is lower than or equal to that number.
     *
     * @param Decimal|number|string $that
     *
     * @return boolean
     */
    public function isLessThanOrEqualTo($that)
    {
        $that = Decimal::of($that);

        return $this->compareTo($that) <= 0;
    }

    /**
     * Checks if this number is strictly greater than that number.
     *
     * @param Decimal|number|string $that
     *
     * @return boolean
     */
    public function isGreaterThan($that)
    {
        $that = Decimal::of($that);

        return $this->compareTo($that) > 0;
    }

    /**
     * Checks if this number is greater than or equal to that number.
     *
     * @param Decimal|number|string $that
     *
     * @return boolean
     */
    public function isGreaterThanOrEqualTo($that)
    {
        $that = Decimal::of($that);

        return $this->compareTo($that) >= 0;
    }

    /**
     * Checks if this number equals zero.
     *
     * @return boolean
     */
    public function isZero()
    {
        return $this->isEqualTo(Decimal::zero());
    }

    /**
     * Checks if this number is strictly negative.
     *
     * @return boolean
     */
    public function isNegative()
    {
        return $this->isLessThan(Decimal::zero());
    }

    /**
     * Checks if this number is negative or zero.
     *
     * @return boolean
     */
    public function isNegativeOrZero()
    {
        return $this->isLessThanOrEqualTo(Decimal::zero());
    }

    /**
     * Checks if this number is strictly positive.
     *
     * @return boolean
     */
    public function isPositive()
    {
        return $this->isGreaterThan(Decimal::zero());
    }

    /**
     * Checks if this number is positive or zero.
     *
     * @return boolean
     */
    public function isPositiveOrZero()
    {
        return $this->isGreaterThanOrEqualTo(Decimal::zero());
    }

    /**
     * @return integer
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Returns a string representation of this number.
     *
     * @return string
     */
    public function toString()
    {
        // ensure that the trailing zeros are returned
        return bcadd($this->value, '0', $this->scale);
    }

    /**
     * Returns a string representation of this number.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
