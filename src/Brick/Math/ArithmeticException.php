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
    public static function inexactResult()
    {
        return new self('Cannot represent the exact result of the operation at this scale, rounding required.');
    }
}
