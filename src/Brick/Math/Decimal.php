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

        return $zero ?: $zero = new self('0');
    }

    /**
     * Returns a decimal number representing one.
     *
     * @return Decimal
     */
    public static function one()
    {
        static $one;

        return $one ?: $one = new self('1');
    }

    /**
     * Creates a decimal of the given value.
     *
     * This method strictly accepts integers and string representations only.
     * Floating point values are imprecise by design, thus if you want to convert a float
     * to a decimal, you have to cast it to a string first, and by doing so you explicitly
     * take responsibility of the potential loss of precision.
     *
     * @param integer|string $value
     *
     * @return Decimal
     *
     * @throws \InvalidArgumentException
     */
    public static function of($value)
    {
        if (is_int($value)) {
            return new self((string) $value);
        }

        if (is_string($value)) {
            if (preg_match(self::DECIMAL_REGEXP, $value, $matches) == 0) {
                $message = 'String is not a valid decimal number: ' . $value;
                throw new \InvalidArgumentException($message);
            }

            $value = $matches[0];
            $scale = isset($matches[1]) ? strlen($matches[1]) : 0;

            return new self($value, $scale);
        }

        throw new \InvalidArgumentException('Expected integer or string, got ' . gettype($value) . '.');
    }

    /**
     * Adds a Decimal number to this number.
     *
     * @param Decimal $that
     *
     * @return Decimal
     */
    public function plus(Decimal $that)
    {
        $scale = max($this->scale, $that->scale);
        $value = bcadd($this->value, $that->value, $scale);

        return new self($value, $scale);
    }

    /**
     * Subtracts a Decimal number from this number.
     *
     * @param Decimal $that
     *
     * @return Decimal
     */
    public function minus(Decimal $that)
    {
        $scale = max($this->scale, $that->scale);
        $value = bcsub($this->value, $that->value, $scale);

        return new self($value, $scale);
    }

    /**
     * Multiplies a Decimal number with this number.
     *
     * @param Decimal $that
     *
     * @return Decimal
     */
    public function multipliedBy(Decimal $that)
    {
        $scale = $this->scale + $that->scale;
        $value = bcmul($this->value, $that->value, $scale);

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
     * @param Decimal      $that
     * @param integer|null $scale
     * @param integer      $roundingMode
     *
     * @return Decimal
     *
     * @throws ArithmeticException
     * @throws \InvalidArgumentException
     */
    public function dividedBy(Decimal $that, $scale = null, $roundingMode = RoundingMode::UNNECESSARY)
    {
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

        return new self($result, $scale);
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

        return $this->dividedBy(self::one(), $scale, $roundingMode);
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
     * Returns the inverse of this Decimal number.
     *
     * @return Decimal
     */
    public function negated()
    {
        return self::zero()->minus($this);
    }

    /**
     * Compares two Decimal numbers.
     *
     * @param Decimal $that
     *
     * @return integer [-1,0,1]
     */
    private function compareTo(Decimal $that)
    {
        return bccomp($this->value, $that->value, max($this->scale, $that->scale));
    }

    /**
     * Checks if this number is equal to that number.
     *
     * @param Decimal $that
     *
     * @return boolean
     */
    public function isEqualTo(Decimal $that)
    {
        return $this->compareTo($that) == 0;
    }

    /**
     * Checks if this number is strictly lower than that number.
     *
     * @param Decimal $that
     * @return boolean
     */
    public function isLessThan(Decimal $that)
    {
        return $this->compareTo($that) < 0;
    }

    /**
     * Checks if this number is lower than or equal to that number.
     *
     * @param  Decimal $that
     * @return boolean
     */
    public function isLessThanOrEqualTo(Decimal $that)
    {
        return $this->compareTo($that) <= 0;
    }

    /**
     * Checks if this number is strictly greater than that number.
     *
     * @param  Decimal $that
     * @return boolean
     */
    public function isGreaterThan(Decimal $that)
    {
        return $this->compareTo($that) > 0;
    }

    /**
     * Checks if this number is greater than or equal to that number.
     *
     * @param  Decimal $that
     * @return boolean
     */
    public function isGreaterThanOrEqualTo(Decimal $that)
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
