<?php

namespace Brick\DateTime\Clock;

use Brick\DateTime\Duration;
use Brick\Math\Math;

/**
 * Clock that adds an offset to an underlying clock.
 */
class OffsetClock implements Clock
{
    /**
     * The reference Clock.
     *
     * @var Clock
     */
    private $baseClock;

    /**
     * The offset in seconds.
     *
     * @var int
     */
    private $offset;

    /**
     * Constructor.
     *
     * @param Clock    $baseClock The reference clock.
     * @param Duration $offset    The offset to apply to the clock.
     */
    public function __construct(Clock $baseClock, Duration $offset)
    {
        $this->baseClock = $baseClock;
        $this->offset = $offset->getSeconds();
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return $this->baseClock->getTimestamp() + $this->offset;
    }
}
