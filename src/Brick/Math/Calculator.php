<?php

namespace Brick\Math;

/**
 * Performs basic operations on arbitrary size integers.
 *
 * All parameters must be validated as non-empty strings of digits,
 * without leading zero, and with an optional leading minus sign.
 * Any other parameter format will lead to undefined behaviour.
 * All methods return a string respecting this format.
 */
abstract class Calculator
{
    /**
     * The Calculator instance to use for all calculations.
     *
     * @var \Brick\Math\Calculator|null
     */
    private static $instance = null;

    /**
     * Sets the Calculator instance to use.
     *
     * An instance is typically set only in unit tests: the autodetect is usually the best option.
     *
     * @param \Brick\Math\Calculator $calculator
     *
     * @return void
     */
    public static function set(Calculator $calculator)
    {
        self::$instance = $calculator;
    }

    /**
     * Returns the Calculator instance to use.
     *
     * If none has been explicitly set, the fastest available implementation will be returned.
     *
     * @return \Brick\Math\Calculator
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = self::detect();
        }

        return self::$instance;
    }

    /**
     * Returns the fastest available Calculator implementation.
     *
     * @return \Brick\Math\Calculator
     */
    private static function detect()
    {
        if (extension_loaded('gmp')) {
            return new Calculator\GmpCalculator();
        }

        if (extension_loaded('bcmath')) {
            return new Calculator\BcMathCalculator();
        }

        throw new \RuntimeException('No math library is available. A native implementation is not available yet.');
    }

    /**
     * Returns the absolute value of a number.
     *
     * @param string $n The number.
     *
     * @return string The absolute value.
     */
    public function abs($n)
    {
        return ($n[0] === '-') ? substr($n, 1) : $n;
    }

    /**
     * Negates a number.
     *
     * @param string $n The number.
     *
     * @return string The inverse value.
     */
    public function neg($n)
    {
        return ($n[0] === '-') ? substr($n, 1) : '-' . $n;
    }

    /**
     * Returns an integer representing the sign of the given number.
     *
     * * -1 if the number is negative
     * *  0 if the number is zero
     * *  1 if the number is positive
     *
     * @param string $n
     *
     * @return integer [-1, 0, 1]
     */
    public function sign($n)
    {
        if ($n === '0') {
            return 0;
        }

        if ($n[0] === '-') {
            return -1;
        }

        return 1;
    }

    /**
     * Compares two numbers.
     *
     * @param string $a The first number.
     * @param string $b The second number.
     *
     * @return integer [-1, 0, 1]
     */
    public function cmp($a, $b)
    {
        return $this->sign($this->sub($a, $b));
    }

    /**
     * Adds two numbers.
     *
     * @param string $a The augend.
     * @param string $b The addend.
     *
     * @return string The sum.
     */
    abstract public function add($a, $b);

    /**
     * Subtracts two numbers.
     *
     * @param string $a The minuend.
     * @param string $b The subtrahend.
     *
     * @return string The difference.
     */
    abstract public function sub($a, $b);

    /**
     * Multiplies two numbers.
     *
     * @param string $a The multiplicand.
     * @param string $b The multiplier.
     *
     * @return string The product.
     */
    abstract public function mul($a, $b);

    /**
     * Divides two numbers.
     *
     * @param string $a The dividend.
     * @param string $b The divisor, must not be zero.
     * @param string $r A variable to store the remainder of the division.
     *
     * @return string The quotient.
     */
    abstract public function div($a, $b, & $r);
}
