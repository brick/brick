<?php

namespace Brick\Math;

/**
 * Exception for arithmetic operations.
 */
class ArithmeticException extends \RuntimeException
{
    /**
     * @return ArithmeticException
     */
    public static function integerOverflow()
    {
        return new self('Out of range for an integer.');
    }

    /**
     * @return ArithmeticException
     */
    public static function divisionByZero()
    {
        return new self('Division by zero.');
    }

    /**
     * @return ArithmeticException
     */
    public static function roundingNecessary()
    {
        return new self('Rounding is necessary to represent the result of the operation at this scale.');
    }
}
