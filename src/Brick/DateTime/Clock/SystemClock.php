<?php

namespace Brick\DateTime\Clock;

/**
 * Clock that returns the system time.
 * This is the default clock.
 */
class SystemClock implements Clock
{
    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return time();
    }
}
