<?php

namespace Brick\DateTime\Utility;

/**
 * Internal utility class for calculations on integers.
 */
class Math
{
    /**
     * Returns the quotient of the division of two integers.
     *
     * @param integer $a The dividend, validated as an integer.
     * @param integer $b The divisor, validated as an integer.
     * @param integer $r An optional variable to store the remainder of the division.
     *
     * @return integer The quotient of the division.
     */
    public static function div($a, $b, & $r = null)
    {
        return ($a - ($r = $a % $b)) / $b;
    }

    /**
     * Returns the largest integer value that is less than or equal to the algebraic quotient.
     *
     * @param integer $a The first argument, validated as an integer.
     * @param integer $b The second argument, validated as an integer.
     *
     * @return integer
     */
    public static function floorDiv($a, $b)
    {
        return ($a >= 0) ? self::div($a, $b) : self::div($a + 1, $b) - 1;
    }

    /**
     * Returns the floor modulus of the int arguments.
     *
     * @param integer $a The first argument, validated as an integer.
     * @param integer $b The second argument, validated as an integer.
     *
     * @return integer
     */
    public static function floorMod($a, $b)
    {
        return (($a % $b) + $b) % $b;
    }
}
