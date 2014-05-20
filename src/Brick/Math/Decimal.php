<?php

namespace Brick\Math;

use Brick\Type\Cast;

/**
 * Immutable, arbitrary-precision signed decimal numbers.
 */
class Decimal
{
    /**
     * The unscaled value of this decimal number.
     *
     * This is a string of digits with an optional leading minus sign.
     * No leading zero must be present.
     * No leading minus sign must be present if the value is 0.
     *
     * @var string
     */
    private $value;

    /**
     * The scale (number of digits after the decimal point) of this decimal number.
     *
     * @var integer
     */
    private $scale;

    /**
     * Private constructor. Use the factory methods.
     *
     * @param string  $value The unscaled value, validated.
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
     * Returns a decimal number representing ten.
     *
     * @return Decimal
     */
    public static function ten()
    {
        static $ten;

        return $ten ?: $ten = new Decimal('10');
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
     * Note: you should avoid passing floating point numbers to this method.
     * Being imprecise by design, they might not convert to the decimal value you expect.
     * This would defeat the whole purpose of using the Decimal type.
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

        if (preg_match('/^\-?[0-9]+(?:\.([0-9]+))?$/', $value, $matches) == 0) {
            throw new \InvalidArgumentException(sprintf('%s does not represent a valid decimal number.', $value));
        }

        $value = str_replace('.', '', $matches[0]);
        $negative = ($value[0] === '-');

        $value = ltrim($value, '-0');

        if ($value === '') {
            $value = '0';
        }

        if ($negative && $value !== '0') {
            $value = '-' . $value;
        }

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
        $this->scaleValues($this, $that, $a, $b);

        $value = Calculator::get()->add($a, $b);
        $scale = $this->scale > $that->scale ? $this->scale : $that->scale;

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
        $this->scaleValues($this, $that, $a, $b);

        $value = Calculator::get()->sub($a, $b);
        $scale = $this->scale > $that->scale ? $this->scale : $that->scale;

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

        $value = Calculator::get()->mul($this->value, $that->value);
        $scale = $this->scale + $that->scale;

        return new Decimal($value, $scale);
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

        $p = $this->valueWithMinScale($that->scale + $scale);
        $q = $that->valueWithMinScale($this->scale - $scale);

        $calculator = Calculator::get();

        $result = $calculator->div($p, $q, $remainder);

        $hasDiscardedFraction = ($remainder !== '0');
        $isPositiveOrZero = ($p[0] === '-') === ($q[0] === '-');

        $double = $calculator->mul($remainder, '2');
        $diff = $calculator->sub($calculator->abs($double), $calculator->abs($q));

        $isDiscardedFractionHalfOrMore   = ($diff[0] !== '-');
        $isDiscardedFractionMoreThanHalf = $isDiscardedFractionHalfOrMore && ($diff !== '0');

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
                $lastDigit = (int) substr($result, -1);
                $lastDigitIsEven = ($lastDigit % 2 === 0);
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
            if ($isPositiveOrZero) {
                $result = $calculator->add($result, '1');
            } else {
                $result = $calculator->sub($result, '1');
            }
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
        return new Decimal(Calculator::get()->neg($this->value), $this->scale);
    }

    /**
     * Compares this number to the given one.
     *
     * @param Decimal|number|string $that
     *
     * @return integer [-1,0,1]
     */
    public function compareTo($that)
    {
        $that = Decimal::of($that);
        $this->scaleValues($this, $that, $a, $b);

        return Calculator::get()->cmp($a, $b);
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
        return $this->compareTo($that) === 0;
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
    public function getUnscaledValue()
    {
        return $this->value;
    }

    /**
     * @return integer
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Puts the unscaled values on the given decimal numbers on the same scale.
     *
     * @param Decimal $x The first decimal number.
     * @param Decimal $y The second decimal number.
     * @param string  $a A variable to store the scaled integer value of $x.
     * @param string  $b A variable to store the scaled integer value of $y.
     *
     * @return void
     */
    private function scaleValues(Decimal $x, Decimal $y, & $a, & $b)
    {
        $a = $x->value;
        $b = $y->value;

        if ($x->scale > $y->scale) {
            $b .= str_repeat('0', $x->scale - $y->scale);
        } elseif ($x->scale < $y->scale) {
            $a .= str_repeat('0', $y->scale - $x->scale);
        }
    }

    /**
     * @param integer $scale
     *
     * @return string
     */
    private function valueWithMinScale($scale)
    {
        $value = $this->value;

        if ($scale > $this->scale) {
            $value .= str_repeat('0', $scale - $this->scale);
        }

        return $value;
    }

    /**
     * Returns a string representation of this number.
     *
     * @return string
     */
    public function toString()
    {
        if ($this->scale === 0) {
            return $this->value;
        }

        $value = $this->value;
        $isNegative = ($this->value[0] === '-');

        if ($isNegative) {
            $value = substr($value, 1);
        }

        $value = str_pad($value, $this->scale + 1, '0', STR_PAD_LEFT);
        $result = substr($value, 0, -$this->scale) . '.' . substr($value, -$this->scale);

        if ($isNegative) {
            $result = '-' . $result;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
