<?php

namespace Brick\Math;

/**
 * Utility class for mathematical functions.
 */
final class Math
{
    /**
     * This is a non-instantiable utility class.
     */
    private function __construct()
    {
    }

    /**
     * Returns the integer addition of two integers.
     *
     * @param int $a The first argument, validated as an integer.
     * @param int $b The second argument, validated as an integer.
     *
     * @return int The sum of the two integers.
     *
     * @throws ArithmeticException If an integer overflow occurs.
     */
    public static function intAdd($a, $b)
    {
        $result = $a + $b;

        if (is_int($result)) {
            return $result;
        }

        throw ArithmeticException::integerOverflow();
    }

    /**
     * Returns the integer subtraction of two integers.
     *
     * @param int $a The first argument, validated as an integer.
     * @param int $b The second argument, validated as an integer.
     *
     * @return int The difference of the two integers.
     *
     * @throws ArithmeticException If an integer overflow occurs.
     */
    public static function intSub($a, $b)
    {
        $result = $a - $b;

        if (is_int($result)) {
            return $result;
        }

        throw ArithmeticException::integerOverflow();
    }

    /**
     * Returns the integer multiplication of two integers.
     *
     * @param int $a The first argument, validated as an integer.
     * @param int $b The second argument, validated as an integer.
     *
     * @return int The product of the two integers.
     *
     * @throws ArithmeticException If an integer overflow occurs.
     */
    public static function intMul($a, $b)
    {
        $result = $a * $b;

        if (is_int($result)) {
            return $result;
        }

        throw ArithmeticException::integerOverflow();
    }

    /**
     * Returns the quotient of the division of two integers.
     *
     * @param int $a The dividend, validated as an integer.
     * @param int $b The divisor, validated as an integer.
     *
     * @return int The quotient of the division.
     *
     * @throws ArithmeticException If an integer overflow occurs.
     */
    public static function intDiv($a, $b)
    {
        if ($b == 0) {
            throw ArithmeticException::divisionByZero();
        }

        return ($a - ($a % $b)) / $b;
    }

    /**
     * @param int $a
     * @param int $b
     * @return int
     * @throws ArithmeticException
     */
    public static function floorDiv($a, $b)
    {
        if ($a >= 0) {
            return self::intDiv($a, $b);
        }

        return self::intSub(self::intDiv(self::intAdd($a, 1),  $b), 1);
    }

    /**
     * @param int $a The first argument, validated as an integer.
     * @param int $b The second argument, validated as an integer.
     *
     * @return int
     */
    public static function floorMod($a, $b)
    {
        return (($a % $b) + $b) % $b;
    }
}
