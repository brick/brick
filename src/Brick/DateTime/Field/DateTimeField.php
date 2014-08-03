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
     * The proleptic year, such as 2012.
     */
    const YEAR = 'year';

    /**
     * The month-of-year, such as March.
     */
    const MONTH_OF_YEAR = 'month-of-year';

    /**
     * The day-of-month.
     */
    const DAY_OF_MONTH = 'day-of-month';

    /**
     * The clock-hour-of-day.
     */
    const HOUR_OF_DAY = 'hour-of-day';

    /**
     * The minute-of-hour.
     */
    const MINUTE_OF_HOUR = 'minute-of-hour';

    /**
     * The second-of-minute.
     */
    const SECOND_OF_MINUTE = 'second-of-minute';

    /**
     * The nano-of-second.
     */
    const NANO_OF_SECOND = 'nano-of-second';

    /**
     * The offset from UTC/Greenwich.
     */
    const OFFSET_SECONDS = 'offset-seconds';

    const TIME_ZONE_OFFSET = 'time-zone-offset';
    const TIME_ZONE_REGION = 'time-zone-region';
}
