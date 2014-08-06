<?php

namespace Brick\DateTime\Field;

use Brick\DateTime\DateTimeException;

/**
 * The hour-of-day field.
 *
 * @internal
 */
class HourOfDay
{
    /**
     * The field name.
     */
    const NAME = 'hour-of-day';

    /**
     * @param integer $hourOfDay The hour-of-day to check, validated as an integer.
     *
     * @return void
     *
     * @throws DateTimeException If the hour-of-day is not valid.
     */
    public static function check($hourOfDay)
    {
        if ($hourOfDay < 0 || $hourOfDay > 23) {
            throw DateTimeException::fieldNotInRange(self::NAME, $hourOfDay, 0, 23);
        }
    }
}
