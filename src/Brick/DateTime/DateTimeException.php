<?php

namespace Brick\DateTime;

/**
 * This exception is used to indicate problems with creating, querying
 * and manipulating date-time objects.
 */
class DateTimeException extends \RuntimeException
{
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
