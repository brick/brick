<?php

namespace Brick\DateTime\Field;

use Brick\DateTime\DateTimeException;

/**
 * The second-of-minute field.
 *
 * @internal
 */
class SecondOfMinute
{
    /**
     * The field name.
     */
    const NAME = 'second-of-minute';

    /**
     * @param integer $secondOfMinute The second-of-minute to check, validated as an integer.
     *
     * @return void
     *
     * @throws DateTimeException If the second-of-minute is not valid.
     */
    public static function check($secondOfMinute)
    {
        if ($secondOfMinute < 0 || $secondOfMinute > 59) {
            throw DateTimeException::fieldNotInRange(self::NAME, $secondOfMinute, 0, 59);
        }
    }
}
