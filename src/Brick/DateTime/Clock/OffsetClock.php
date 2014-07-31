<?php

namespace Brick\DateTime\Clock;

use Brick\DateTime\Duration;

/**
 * This clock adds an offset to an underlying clock.
 */
class OffsetClock implements Clock
{
    /**
     * The reference clock.
     *
     * @var Clock
     */
    private $clock;

    /**
     * The offset to apply to the clock.
     *
     * @var Duration
     */
    private $offset;

    /**
     * Class constructor.
     *
     * @param Clock    $clock  The reference clock.
     * @param Duration $offset The offset to apply to the clock.
     */
    public function __construct(Clock $clock, Duration $offset)
    {
        $this->clock  = $clock;
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getTime()
    {
        return $this->clock->getTime()->plus($this->offset);
    }
}
