<?php

namespace Brick\DateTime\Utility;

use Brick\DateTime\LocalTime;

/**
 * Internal utility class for calculations on time.
 */
class Time
{
    /**
     * Adds two times consisting of seconds and nanoseconds.
     *
     * @param integer $seconds1 The seconds of the 1st time.
     * @param integer $nanos1   The nanoseconds of the 1st time.
     * @param integer $seconds2 The seconds of the 2nd time.
     * @param integer $nanos2   The nanoseconds of the 2nd time.
     * @param integer $seconds  A variable to store the seconds of the result.
     * @param integer $nanos    A variable to store the nanoseconds of the result.
     *
     * @return void
     */
    public static function add($seconds1, $nanos1, $seconds2, $nanos2, & $seconds, & $nanos)
    {
        $seconds = $seconds1 + $seconds2;
        $nanos = $nanos1 + $nanos2;

        if ($nanos >= LocalTime::NANOS_PER_SECOND) {
            $nanos -= LocalTime::NANOS_PER_SECOND;
            $seconds++;
        }
    }
}
