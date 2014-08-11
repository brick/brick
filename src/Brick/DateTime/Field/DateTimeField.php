<?php

namespace Brick\DateTime\Field;

/**
 * The date-time field constants.
 */
final class DateTimeField
{
    /**
     * Private constructor. This class cannot be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * The proleptic year: integer from MIN_YEAR to MAX_YEAR.
     */
    const YEAR = 'year';

    /**
     * The month-of-year: integer from 1 to 12.
     */
    const MONTH_OF_YEAR = 'month-of-year';

    /**
     * The day-of-month: integer from 1 to 31.
     */
    const DAY_OF_MONTH = 'day-of-month';

    /**
     * The hour-of-day: integer from 0 to 23.
     */
    const HOUR_OF_DAY = 'hour-of-day';

    /**
     * The minute-of-hour: integer from 0 to 59.
     */
    const MINUTE_OF_HOUR = 'minute-of-hour';

    /**
     * The second-of-minute: integer from 0 to 59.
     */
    const SECOND_OF_MINUTE = 'second-of-minute';

    /**
     * The nano-of-second: integer from 0 to 999,999,999.
     */
    const NANO_OF_SECOND = 'nano-of-second';

    /**
     * The sign of the time-zone offset: string '+' or '-', 'z' or 'Z'.
     *
     * If the sign case-insensitively matches 'Z', the offset is of zero seconds (UTC)
     * and is not followed by the hour/minute/second notation.
     */
    const TIME_ZONE_OFFSET_SIGN = 'offset-sign';

    /**
     * The absolute time-zone offset hour: integer from 0 to 23.
     */
    const TIME_ZONE_OFFSET_HOUR = 'offset-hour';

    /**
     * The absolute time-zone offset minute: integer from 0 to 59.
     */
    const TIME_ZONE_OFFSET_MINUTE = 'offset-minute';

    /**
     * The absolute time-zone offset second: integer from 0 to 59.
     */
    const TIME_ZONE_OFFSET_SECOND = 'offset-second';

    /**
     * The time-zone region: string such as 'Europe/London'.
     */
    const TIME_ZONE_REGION = 'time-zone-region';
}
