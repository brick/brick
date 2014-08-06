<?php

namespace Brick\DateTime\Field;

use Brick\DateTime\DateTimeException;

/**
 * The time-zone offset seconds field.
 *
 * The offset is relative to UTC/Greenwich.
 *
 * @internal
 */
class OffsetSeconds
{
    /**
     * The field name.
     */
    const NAME = 'offset-seconds';

    /**
     * The absolute maximum seconds.
     */
    const MAX_SECONDS = 64800;

    /**
     * @param integer $offsetSeconds The offset-seconds to check, validated as an integer.
     *
     * @return void
     *
     * @throws DateTimeException If the offset-seconds is not valid.
     */
    public static function check($offsetSeconds)
    {
        if ($offsetSeconds < -self::MAX_SECONDS || $offsetSeconds > self::MAX_SECONDS) {
            throw DateTimeException::fieldNotInRange(self::NAME, $offsetSeconds, -self::MAX_SECONDS, self::MAX_SECONDS);
        }
    }
}
