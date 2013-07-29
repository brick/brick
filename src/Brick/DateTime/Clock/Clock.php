<?php

namespace Brick\DateTime\Clock;

/**
 * A clock provides the current time.
 */
interface Clock
{
    /**
     * Returns the current UNIX timestamp.
     *
     * @return int
     */
    public function getTimestamp();
}
