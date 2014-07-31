<?php

namespace Brick\DateTime;

/**
 * Base class for Instant and ZonedDateTime.
 */
abstract class ReadableInstant
{
    /**
     * @return \Brick\DateTime\Instant
     */
    abstract public function getInstant();

    /**
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->getInstant()->getTimestamp();
    }

    /**
     * @return integer
     */
    public function getMicroseconds()
    {
        return $this->getInstant()->getMicroseconds();
    }

    /**
     * Compares this instant with another.
     *
     * Returns:
     *
     * * a negative number if this instant is before the given one;
     * * a positive number if this instant is after the given one;
     * * zero if this instant equals the given one.
     *
     * @param ReadableInstant $other
     * @return int
     */
    public function compareTo(ReadableInstant $other)
    {
        return $this->getInstant()->getTimestamp() - $other->getInstant()->getTimestamp();
    }

    /**
     * Returns whether this instant equals another.
     *
     * @param ReadableInstant $other
     * @return bool
     */
    public function isEqualTo(ReadableInstant $other)
    {
        return $this->compareTo($other) == 0;
    }

    /**
     * Returns whether this instant is after another.
     *
     * @param ReadableInstant $other
     * @return bool
     */
    public function isAfter(ReadableInstant $other)
    {
        return $this->compareTo($other) > 0;
    }

    /**
     * Returns whether this instant is after, or equal to, another.
     *
     * @param ReadableInstant $other
     * @return bool
     */
    public function isAfterOrEqualTo(ReadableInstant $other)
    {
        return $this->compareTo($other) >= 0;
    }

    /**
     * Returns whether this instant is before another.
     *
     * @param ReadableInstant $other
     * @return bool
     */
    public function isBefore(ReadableInstant $other)
    {
        return $this->compareTo($other) < 0;
    }

    /**
     * Returns whether this instant is before, or equal to, another.
     *
     * @param ReadableInstant $other
     * @return bool
     */
    public function isBeforeOrEqualTo(ReadableInstant $other)
    {
        return $this->compareTo($other) <= 0;
    }

    /**
     * Returns whether this instant is between the given instants, inclusive.
     *
     * @param ReadableInstant $first
     * @param ReadableInstant $last
     * @return bool
     */
    public function isBetweenInclusive(ReadableInstant $first, ReadableInstant $last)
    {
        return $this->isAfterOrEqualTo($first) && $this->isBeforeOrEqualTo($last);
    }

    /**
     * Returns whether this instant is between the given instants, exclusive.
     *
     * @param ReadableInstant $first
     * @param ReadableInstant $last
     * @return bool
     */
    public function isBetweenExclusive(ReadableInstant $first, ReadableInstant $last)
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
