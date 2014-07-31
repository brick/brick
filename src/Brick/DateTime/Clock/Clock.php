<?php

namespace Brick\DateTime\Clock;

/**
 * A clock provides the current time.
 */
interface Clock
{
    /**
     * Returns the current time.
     *
     * @return \Brick\DateTime\Instant
     */
    public function getTime();
}
