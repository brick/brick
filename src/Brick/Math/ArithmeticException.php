<?php

namespace Brick\Math;

/**
 * Exception for arithmetic operations.
 */
class ArithmeticException extends \LogicException
{
    /**
     * @return ArithmeticException
     */
    public static function integerOverflow()
    {
        return new self('Out of range for an integer');
    }

    /**
     * @return ArithmeticException
     */
    public static function divisionByZero()
    {
        return new self('Division by zero');
    }
}
