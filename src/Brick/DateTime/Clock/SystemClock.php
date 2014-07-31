<?php

namespace Brick\DateTime\Clock;

use Brick\DateTime\Instant;

/**
 * This clock returns the system time. It is the default clock.
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
        $microseconds = (int) substr($fraction, 2, 6);

        return Instant::of($timestamp, $microseconds);
    }
}
