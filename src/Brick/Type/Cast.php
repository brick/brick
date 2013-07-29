<?php

namespace Brick\Type;

/**
 * Collection of methods to cast variables.
 */
final class Cast
{
    /**
     * Private constructor. This class cannot be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Casts a variable to an integer.
     *
     * @param mixed $value The value to cast.
     *
     * @return int The integer value of the variable.
     *
     * @throws \InvalidArgumentException If the variable cannot be casted to an integer.
     */
    public static function toInteger($value)
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        if (is_int($integer)) {
            return $integer;
        }

        throw new \InvalidArgumentException('Expected integer, got ' . gettype($value));
    }

    /**
     * Casts a variable to a string.
     *
     * @param mixed $value The value to cast.
     *
     * @return string The variable as a string.
     *
     * @throws \InvalidArgumentException If the variable cannot be casted to a string.
     */
    public static function toString($value)
    {
        if (is_scalar($value)) {
            return (string) $value;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        throw new \InvalidArgumentException('Expected string, got ' . gettype($value));
    }
}
