<?php

namespace Brick\DateTime;

/**
 * This exception is used to indicate problems with creating, querying
 * and manipulating date-time objects.
 */
class DateTimeException extends \RuntimeException
{
    /**
     * @param string  $name   A meaningful name for the tested value.
     * @param integer $min    The minimum allowed value.
     * @param integer $max    The maximum allowed value.
     * @param integer $actual The actual value.
     *
     * @return DateTimeException
     */
    public static function notInRange($name, $min, $max, $actual)
    {
        return new self(sprintf(
            '%s must be in the range %d to %d, got %d.',
            $name,
            $min,
            $max,
            $actual
        ));
    }

    /**
     * @param string $region
     *
     * @return DateTimeException
     */
    public static function unknownTimeZoneRegion($region)
    {
        return new self(sprintf('Unknown time zone region "%s".', $region));
    }
}
