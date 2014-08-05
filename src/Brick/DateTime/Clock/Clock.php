<?php

namespace Brick\DateTime\Clock;

/**
 * A clock provides the current time.
 */
abstract class Clock
{
    /**
     * The global default Clock.
     *
     * @var \Brick\DateTime\Clock\Clock|null
     */
    private static $default = null;

    /**
     * Sets the default clock.
     *
     * @param \Brick\DateTime\Clock\Clock $clock
     *
     * @return void
     */
    public static function setDefault(Clock $clock)
    {
        self::$default = $clock;
    }

    /**
     * Returns the default clock. Defaults to the system clock unless overridden.
     *
     * @return \Brick\DateTime\Clock\Clock
     */
    public static function getDefault()
    {
        if (self::$default === null) {
            self::$default = new SystemClock();
        }

        return self::$default;
    }

    /**
     * Returns the current time.
     *
     * @return \Brick\DateTime\Instant
     */
    abstract public function getTime();
}
