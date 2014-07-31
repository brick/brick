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
        return Instant::of(time());
    }
}
