<?php

namespace Brick\DateTime;

use Brick\DateTime\Utility\Cast;

/**
 * A date-based amount of time in the ISO-8601 calendar system, such as '2 years, 3 months and 4 days'.
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
     * Private constructor. Use of() to obtain an instance.
     *
     * @param integer $years   The number of years, validated as an integer.
     * @param integer $months  The number of months, validated as an integer.
     * @param integer $days    The number of days, validated as an integer.
     */
    private function __construct($years, $months, $days)
    {
        $this->years   = $years;
        $this->months  = $months;
        $this->days    = $days;
    }

    /**
     * Creates a Period based on years, months, days, hours, minutes and seconds.
     *
     * @param integer $years   The number of years.
     * @param integer $months  The number of months.
     * @param integer $days    The number of days.
     *
     * @return Period
     */
    public static function of($years, $months, $days)
    {
        return new Period(
            Cast::toInteger($years),
            Cast::toInteger($months),
            Cast::toInteger($days)
        );
    }

    /**
     * Creates a zero Period.
     *
     * @return Period
     */
    public static function zero()
    {
        return new Period(0, 0, 0);
    }

    /**
     * Returns a Period consisting of the number of years, months and days between two LocalDateTime instances.
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
     * @param integer $years
     *
     * @return Period
     */
    public function plusYears($years)
    {
        $years = Cast::toInteger($years);

        if ($years === 0) {
            return $this;
        }

        return new Period($this->years + $years, $this->months, $this->days);
    }

    /**
     * @param integer $months
     *
     * @return Period
     */
    public function plusMonths($months)
    {
        $months = Cast::toInteger($months);

        if ($months === 0) {
            return $this;
        }

        return new Period($this->years, $this->months + $months, $this->days);
    }

    /**
     * @param integer $days
     *
     * @return Period
     */
    public function plusDays($days)
    {
        $days = Cast::toInteger($days);

        if ($days === 0) {
            return $this;
        }

        return new Period($this->years, $this->months, $this->days + $days);
    }

    /**
     * @param integer $years
     *
     * @return Period
     */
    public function withYears($years)
    {
        $years = Cast::toInteger($years);

        if ($years === $this->years) {
            return $this;
        }

        return new Period($years, $this->months, $this->days);
    }

    /**
     * @param integer $months
     *
     * @return Period
     */
    public function withMonths($months)
    {
        $months = Cast::toInteger($months);

        if ($months === $this->months) {
            return $this;
        }

        return new Period($this->years, $months, $this->days);
    }

    /**
     * @param integer $days
     *
     * @return Period
     */
    public function withDays($days)
    {
        $days = Cast::toInteger($days);

        if ($days === $this->days) {
            return $this;
        }

        return new Period($this->years, $this->months, $days);
    }

    /**
     * Returns a new instance with each amount in this Period negated.
     *
     * @return Period
     */
    public function negated()
    {
        if ($this->isZero()) {
            return $this;
        }

        return new Period(
            - $this->years,
            - $this->months,
            - $this->days
        );
    }

    /**
     * @return boolean
     */
    public function isZero()
    {
        return $this->years === 0 && $this->months === 0 && $this->days === 0;
    }

    /**
     * @param Period $that
     *
     * @return boolean
     */
    public function isEqualTo(Period $that)
    {
        return $this->years === $that->years
            && $this->months === $that->months
            && $this->days === $that->days;
    }

    /**
     * Returns a native DateInterval with the same values as this Period.
     *
     * @return \DateInterval
     */
    public function toDateInterval()
    {
        return \DateInterval::createFromDateString(sprintf(
            '%d years %d months %d days',
            $this->years,
            $this->months,
            $this->days
        ));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->isZero()) {
            return 'P0D';
        }

        $string = 'P';

        if ($this->years !== 0) {
            $string .= $this->years . 'Y';
        }
        if ($this->months !== 0) {
            $string .= $this->months . 'M';
        }
        if ($this->days !== 0) {
            $string .= $this->days . 'D';
        }

        return $string;
    }
}
