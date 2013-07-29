<?php

namespace Brick\DateTime;

/**
 * Represents a year.
 */
class Year
{
    const MIN_YEAR = LocalDate::MIN_YEAR;
    const MAX_YEAR = LocalDate::MAX_YEAR;

    /**
     * The year being represented.
     *
     * @var int
     */
    private $year;

    /**
     * Class constructor.
     *
     * @param int $year The year to represent.
     * @throws \UnexpectedValueException
     *
     * @todo private, checks in of()
     */
    public function __construct($year)
    {
        $this->year = filter_var($year, FILTER_VALIDATE_INT, array(
            'options' => array(
                'min_range' => self::MIN_YEAR,
                'max_range' => self::MAX_YEAR
            )
        ));

        if ($this->year === false) {
            throw new \UnexpectedValueException('Year must be an integer in the interval [0-9999]');
        }
    }

    /**
     * @param int $year
     * @return Year
     */
    public static function of($year)
    {
        return new Year($year);
    }

    /**
     * Checks if the year is a leap year, according to the ISO proleptic calendar system rules.
     *
     * This method applies the current rules for leap years across the whole time-line.
     * In general, a year is a leap year if it is divisible by four without
     * remainder. However, years divisible by 100, are not leap years, with
     * the exception of years divisible by 400 which are.
     *
     * The calculation is proleptic - applying the same rules into the far future and far past.
     * This is historically inaccurate, but is correct for the ISO-8601 standard.
     *
     * @return bool
     */
    public function isLeap()
    {
        return (($this->year & 3) == 0) && (($this->year % 100) != 0 || ($this->year % 400) == 0);
    }

    /**
     * Gets the length of this year in days.
     *
     * @return int The length of this year in days, 365 or 366.
     */
    public function getLength()
    {
        return $this->isLeap() ? 366 : 365;
    }

    /**
     * Returns a copy of this year with the specified number of years added.
     *
     * This instance is immutable and unaffected by this method call.
     *
     * @param int $yearsToAdd The years to add, may be negative.
     * @return Year A Year based on this year with the period added.
     */
    public function plus($yearsToAdd)
    {
        if ($yearsToAdd == 0) {
            return $this;
        }

        return new Year($this->year + $yearsToAdd);
    }

    /**
     * Returns a copy of this year with the specified number of years subtracted.
     *
     * This instance is immutable and unaffected by this method call.
     *
     * @param int $yearsToSubtract The years to subtract, may be negative.
     * @return Year A Year based on this year with the period subtracted.
     */
    public function minus($yearsToSubtract)
    {
        if ($yearsToSubtract == 0) {
            return $this;
        }

        return $this->plus(- $yearsToSubtract);
    }

    /**
     * @return int
     */
    public function toInteger()
    {
        return $this->year;
    }
}
