<?php

namespace Brick\DateTime;

use Brick\Type\Cast;

/**
 * A period consisting of the most common units, such as 3 Months, 4 Days and 7 Hours.
 *
 * This class is immutable.
 */
class Period
{
    /**
     * @var int
     */
    private $years;

    /**
     * @var int
     */
    private $months;

    /**
     * @var int
     */
    private $days;

    /**
     * @var int
     */
    private $hours;

    /**
     * @var int
     */
    private $minutes;

    /**
     * @var int
     */
    private $seconds;

    /**
     * Private constructor. Use of() to obtain an instance.
     *
     * @param int $years   The number of years, validated as an integer.
     * @param int $months  The number of months, validated as an integer.
     * @param int $days    The number of days, validated as an integer.
     * @param int $hours   The number of hours, validated as an integer.
     * @param int $minutes The number of minutes, validated as an integer.
     * @param int $seconds The number of seconds, validated as an integer.
     */
    private function __construct($years, $months, $days, $hours, $minutes, $seconds)
    {
        $this->years = $years;
        $this->months = $months;
        $this->days = $days;
        $this->hours = $hours;
        $this->minutes = $minutes;
        $this->seconds = $seconds;
    }

    /**
     * Creates a Period based on years, months, days, hours, minutes and seconds.
     *
     * @param int $years   The number of years.
     * @param int $months  The number of months.
     * @param int $days    The number of days.
     * @param int $hours   The number of hours.
     * @param int $minutes The number of minutes.
     * @param int $seconds The number of seconds.
     * @return Period
     */
    public static function of($years, $months, $days, $hours, $minutes, $seconds)
    {
        return new Period(
            Cast::toInteger($years),
            Cast::toInteger($months),
            Cast::toInteger($days),
            Cast::toInteger($hours),
            Cast::toInteger($minutes),
            Cast::toInteger($seconds)
        );
    }

    /**
     * Creates a zero Period.
     *
     * @return Period
     */
    public static function zero()
    {
        return new Period(0, 0, 0, 0, 0, 0);
    }

    /**
     * Creates a Period based on hours, minutes and seconds.
     *
     * @param int $hours   The amount of hours, may be negative.
     * @param int $minutes The amount of minutes, may be negative.
     * @param int $seconds The amount of seconds, may be negative.
     * @return Period
     */
    public static function ofTime($hours, $minutes, $seconds)
    {
        return Period::of(0, 0, 0, $hours, $minutes, $seconds);
    }

    /**
     * Returns a Period consisting of the number of years, months, days,
     * hours, minutes, and seconds between two LocalDateTime instances.
     *
     * @param LocalDateTime $startInclusive
     * @param LocalDateTime $endExclusive
     * @return Period
     */
    public static function between(LocalDateTime $startInclusive, LocalDateTime $endExclusive)
    {
        // @todo
    }

    /**
     * @return int
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * @return int
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @return int
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @return int
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * @return int
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * @param int $years
     * @return Period
     */
    public function withYears($years)
    {
        $years = Cast::toInteger($years);

        if ($years == $this->years) {
            return $this;
        }

        return new Period($years, $this->months, $this->days, $this->hours, $this->minutes, $this->seconds);
    }

    /**
     * @param int $months
     * @return Period
     */
    public function withMonths($months)
    {
        $months = Cast::toInteger($months);

        if ($months == $this->months) {
            return $this;
        }

        return new Period($this->years, $months, $this->days, $this->hours, $this->minutes, $this->seconds);
    }

    /**
     * @param int $days
     * @return Period
     */
    public function withDays($days)
    {
        $days = Cast::toInteger($days);

        if ($days == $this->days) {
            return $this;
        }

        return new Period($this->years, $this->months, $days, $this->hours, $this->minutes, $this->seconds);
    }

    /**
     * @param int $hours
     * @return Period
     */
    public function withHours($hours)
    {
        $hours = Cast::toInteger($hours);

        if ($hours == $this->hours) {
            return $this;
        }

        return new Period($this->years, $this->months, $this->days, $hours, $this->minutes, $this->seconds);
    }

    /**
     * @param $minutes
     * @return Period
     */
    public function withMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        if ($minutes == $this->minutes) {
            return $this;
        }

        return new Period($this->years, $this->months, $this->days, $this->hours, $minutes, $this->seconds);
    }

    /**
     * @param int $seconds
     * @return Period
     */
    public function withSeconds($seconds)
    {
        $seconds = Cast::toInteger($seconds);

        if ($seconds == $this->seconds) {
            return $this;
        }

        return new Period($this->years, $this->months, $this->days, $this->hours, $this->minutes, $seconds);
    }

    /**
     * Returns a new instance with each amount in this Period negated.
     *
     * @return Period
     */
    public function negated()
    {
        return new Period(
            - $this->years,
            - $this->months,
            - $this->days,
            - $this->hours,
            - $this->minutes,
            - $this->seconds
        );
    }

    /**
     * Returns the number of seconds as totalled by hours, minutes and seconds.
     *
     * @return int
     */
    private function getTotalSeconds()
    {
        return LocalTime::SECONDS_PER_HOUR * $this->hours
            + LocalTime::MINUTES_PER_HOUR * $this->minutes
            + $this->seconds;
    }

    /**
     * @param Period $period
     * @return bool
     */
    public function isEqualTo(Period $period)
    {
        return $this->years == $period->years
            && $this->months == $period->months
            && $this->days == $period->days
            && $this->getTotalSeconds() == $period->getTotalSeconds();
    }

    /**
     * Returns a native DateInterval with the same values as this Period.
     *
     * @return \DateInterval
     */
    public function toDateInterval()
    {
        return \DateInterval::createFromDateString(sprintf(
            '%d years %d months %d days %d hours %d minutes %d seconds',
            $this->years,
            $this->months,
            $this->days,
            $this->hours,
            $this->minutes,
            $this->seconds
        ));
    }
}
