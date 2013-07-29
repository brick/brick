<?php

namespace Brick\DateTime\Clock;

use Brick\DateTime\PointInTime;
use Brick\Type\Cast;

/**
 * Clock that always returns the same instant.
 * This is typically used for testing.
 */
class FixedClock implements Clock
{
    /**
     * @var int
     */
    private $timestamp;

    /**
     * Private constructor. Use a factory method to obtain a FixedClock.
     *
     * @param int $timestamp The timestamp to set the clock at, validated as an integer.
     */
    private function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Returns a FixedClock set at the given timestamp.
     *
     * @param int $timestamp The timestamp to set the clock at.
     * @return FixedClock
     */
    public static function at($timestamp)
    {
        return new FixedClock(Cast::toInteger($timestamp));
    }

    /**
     * @param \Brick\DateTime\PointInTime $instant
     * @return FixedClock
     */
    public static function atInstant(PointInTime $instant)
    {
        return new FixedClock($instant->getTimestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
