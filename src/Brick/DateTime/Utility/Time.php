<?php

namespace Brick\DateTime\Utility;

/**
 * Internal utility class for calculations on time.
 */
class Time
{
    /**
     * Adds two times consisting of seconds and microseconds.
     *
     * @param integer $seconds1 The seconds of the 1st time.
     * @param integer $micros1  The microseconds of the 1st time.
     * @param integer $seconds2 The seconds of the 2nd time.
     * @param integer $micros2  The microseconds of the 2nd time.
     * @param integer $seconds  A variable to store the seconds of the result.
     * @param integer $micros   A variable to store the microseconds of the result.
     *
     * @return void
     */
    public static function add($seconds1, $micros1, $seconds2, $micros2, & $seconds, & $micros)
    {
        $seconds = $seconds1 + $seconds2;
        $micros = $micros1 + $micros2;

        if ($micros > 999999) {
            $micros -= 1000000;
            $seconds++;
        }
    }
}
