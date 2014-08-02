<?php

namespace Brick\DateTime\Clock;

use Brick\DateTime\Instant;

/**
 * This clock returns the system time. It is the default clock.
 *
 * This clock has a microsecond precision on most systems.
 */
class SystemClock implements Clock
{
    /**
     * {@inheritdoc}
     */
    public function getTime()
    {
        list ($fraction, $timestamp) = explode(' ', microtime());

        $timestamp = (int) $timestamp;
        $nanos = 10 * (int) substr($fraction, 2, 8);

        return Instant::of($timestamp, $nanos);
    }
}
