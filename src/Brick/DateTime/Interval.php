<?php

namespace Brick\DateTime;

/**
 * Represents a period of time between two instants, inclusive of the start instant and exclusive of the end.
 * The end instant is always greater than or equal to the start instant.
 *
 * This class is immutable.
 */
class Interval
{
    /**
     * The start instant, inclusive.
     *
     * @var \Brick\DateTime\PointInTime
     */
    private $start;

    /**
     * The end instant, exclusive.
     *
     * @var \Brick\DateTime\PointInTime
     */
    private $end;

    /**
     * Class constructor.
     *
     * @param \Brick\DateTime\PointInTime $startInclusive The start instant, inclusive.
     * @param \Brick\DateTime\PointInTime $endExclusive   The end instant, exclusive.
     *
     * @throws \Brick\DateTime\DateTimeException
     */
    public function __construct(PointInTime $startInclusive, PointInTime $endExclusive)
    {
        if (! $endExclusive->isAfterOrEqualTo($startInclusive)) {
            throw new DateTimeException('The end instant must be after or equal to the start instant');
        }

        $this->start = $startInclusive;
        $this->end = $endExclusive;
    }

    /**
     * Returns the start instant, inclusive, of this Interval.
     *
     * @return \Brick\DateTime\PointInTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Returns the end instant, exclusive, of this Interval.
     *
     * @return \Brick\DateTime\PointInTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Returns a copy of this Interval with the start instant altered.
     *
     * @param \Brick\DateTime\PointInTime $start
     *
     * @return \Brick\DateTime\Interval
     */
    public function withStart(PointInTime $start)
    {
        return new Interval($start, $this->end);
    }

    /**
     * Returns a copy of this Interval with the end instant altered.
     *
     * @param \Brick\DateTime\PointInTime $end
     *
     * @return \Brick\DateTime\Interval
     */
    public function withEnd(PointInTime $end)
    {
        return new Interval($this->start, $end);
    }

    /**
     * Returns a Duration representing the time elapsed in this Interval.
     *
     * @return \Brick\DateTime\Duration
     */
    public function getDuration()
    {
        return Duration::between($this->start, $this->end);
    }
}
