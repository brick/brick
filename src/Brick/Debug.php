<?php

namespace Brick;

/**
 * Collection of useful methods for debug messages.
 */
class Debug
{
    /**
     * Returns the class name if the variable is an object, or the type if it's not.
     *
     * @param mixed $variable
     * @return string
     */
    public static function getType($variable)
    {
        return is_object($variable) ? get_class($variable) : gettype($variable);
    }

    /**
     * Returns whether the variable is castable to a string.
     *
     * @param mixed $variable
     * @return boolean
     */
    public static function isCastableToString($variable)
    {
        if (is_scalar($variable)) {
            return true;
        }

        if (is_object($variable) && method_exists($variable, '__toString')) {
            return true;
        }

        return false;
    }
}
