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
     * @var integer
     */
    private $years;

    /**
     * @var integer
     */
    private $months;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var integer
     */
    private $hours;

    /**
     * @var integer
     */
    private $minutes;

    /**
     * @var integer
     */
    private $seconds;

    /**
     * Private constructor. Use of() to obtain an instance.
     *
     * @param integer $years   The number of years, validated as an integer.
     * @param integer $months  The number of months, validated as an integer.
     * @param integer $days    The number of days, validated as an integer.
     * @param integer $hours   The number of hours, validated as an integer.
     * @param integer $minutes The number of minutes, validated as an integer.
     * @param integer $seconds The number of seconds, validated as an integer.
     */
    private function __construct($years, $months, $days, $hours, $minutes, $seconds)
    {
        $this->years   = $years;
        $this->months  = $months;
        $this->days    = $days;
        $this->hours   = $hours;
        $this->minutes = $minutes;
        $this->seconds = $seconds;
    }

    /**
     * Creates a Period based on years, months, days, hours, minutes and seconds.
     *
     * @param integer $years   The number of years.
     * @param integer $months  The number of months.
     * @param integer $days    The number of days.
     * @param integer $hours   The number of hours.
     * @param integer $minutes The number of minutes.
     * @param integer $seconds The number of seconds.
     *
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
     * @param integer $hours   The amount of hours, may be negative.
     * @param integer $minutes The amount of minutes, may be negative.
     * @param integer $seconds The amount of seconds, may be negative.
     *
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
     *
     * @return Period
     */
    public static function between(LocalDateTime $startInclusive, LocalDateTime $endExclusive)
    {
        // @todo
    }

    /**
     * @return integer
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * @return integer
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * @return integer
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * @return integer
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @return integer
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * @return integer
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * @param integer $years
     *
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
     * @param integer $months
     *
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
     * @param integer $days
     *
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
     * @param integer $hours
     *
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
     * @param integer $minutes
     *
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
     * @param integer $seconds
     *
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
     * @return integer
     */
    private function getTotalSeconds()
    {
        return LocalTime::SECONDS_PER_HOUR * $this->hours
            + LocalTime::MINUTES_PER_HOUR * $this->minutes
            + $this->seconds;
    }

    /**
     * @param Period $period
     *
     * @return boolean
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
