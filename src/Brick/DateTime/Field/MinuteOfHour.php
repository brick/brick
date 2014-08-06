<?php

namespace Brick\DateTime\Field;

use Brick\DateTime\DateTimeException;

/**
 * The minute-of-hour field.
 *
 * @internal
 */
class MinuteOfHour
{
    /**
     * The field name.
     */
    const NAME = 'minute-of-hour';

    /**
     * @param integer $minuteOfHour The minute-of-hour to check, validated as an integer.
     *
     * @return void
     *
     * @throws DateTimeException If the minute-of-hour is not valid.
     */
    public static function check($minuteOfHour)
    {
        if ($minuteOfHour < 0 || $minuteOfHour > 59) {
            throw DateTimeException::fieldNotInRange(self::NAME, $minuteOfHour, 0, 59);
        }
    }
}
