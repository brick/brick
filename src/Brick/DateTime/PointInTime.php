<?php

namespace Brick\DateTime;

/**
 * Base class for a point-in-time, such as Instant or ZonedDateTime.
 */
abstract class PointInTime
{
    /**
     * @return int
     */
    abstract public function getTimestamp();

    /**
     * Compares this instant with another.
     *
     * Returns:
     *
     * * a negative number if this instant is before the given one;
     * * a positive number if this instant is after the given one;
     * * zero if this instant equals the given one.
     *
     * @param PointInTime $other
     * @return int
     */
    public function compareTo(PointInTime $other)
    {
        return $this->getTimestamp() - $other->getTimestamp();
    }

    /**
     * Returns whether this instant equals another.
     *
     * @param PointInTime $other
     * @return bool
     */
    public function isEqualTo(PointInTime $other)
    {
        return $this->compareTo($other) == 0;
    }

    /**
     * Returns whether this instant is after another.
     *
     * @param PointInTime $other
     * @return bool
     */
    public function isAfter(PointInTime $other)
    {
        return $this->compareTo($other) > 0;
    }

    /**
     * Returns whether this instant is after, or equal to, another.
     *
     * @param PointInTime $other
     * @return bool
     */
    public function isAfterOrEqualTo(PointInTime $other)
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * Returns whether this instant is before another.
     *
     * @param PointInTime $other
     * @return bool
     */
    public function isBefore(PointInTime $other)
    {
        return $this->compareTo($other) < 0;
    }

    /**
     * Returns whether this instant is before, or equal to, another.
     *
     * @param PointInTime $other
     * @return bool
     */
    public function isBeforeOrEqualTo(PointInTime $other)
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Returns whether this instant is between the given instants, inclusive.
     *
     * @param PointInTime $first
     * @param PointInTime $last
     * @return bool
     */
    public function isBetweenInclusive(PointInTime $first, PointInTime $last)
    {
        return $this->isAfterOrEqualTo($first) && $this->isBeforeOrEqualTo($last);
    }

    /**
     * Returns whether this instant is between the given instants, exclusive.
     *
     * @param PointInTime $first
     * @param PointInTime $last
     * @return bool
     */
    public function isBetweenExclusive(PointInTime $first, PointInTime $last)
    {
        return $this->isAfter($first) && $this->isBefore($last);
    }

    /**
     * Returns whether this instant is in the past.
     *
     * @return bool
     */
    public function isPast()
    {
        return $this->isBefore(Instant::now());
    }

    /**
     * Returns whether this instant is in the past or present.
     *
     * @return bool
     */
    public function isPastOrPresent()
    {
        return $this->isBeforeOrEqualTo(Instant::now());
    }

    /**
     * Returns whether this instant is in the future.
     *
     * @return bool
     */
    public function isFuture()
    {
        return $this->isAfter(Instant::now());
    }

    /**
     * Returns whether this instant is in the future or present.
     *
     * @return bool
     */
    public function isFutureOrPresent()
    {
        return $this->isAfterOrEqualTo(Instant::now());
    }
}
