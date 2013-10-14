<?php

namespace Brick\DateTime;

/**
 * Represents the combination of a year and a month.
 */
class YearMonth
{
    /**
     * @var Year
     */
    private $year;

    /**
     * @var Month
     */
    private $month;

    /**
     * Class constructor.
     *
     * @param Year $year
     * @param Month $month
     */
    public function __construct(Year $year, Month $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * @param TimeZone $timezone
     * @return YearMonth
     */
    public static function now(TimeZone $timezone)
    {
        $localDate = LocalDate::now($timezone);

        return new YearMonth(Year::of($localDate->getYear()), Month::of($localDate->getMonth()));
    }
    /**
     * Returns the number of days in this YearMonth.
     *
     * @return int [28-31]
     */
    public function getNumberOfDays()
    {
        return cal_days_in_month(
            CAL_GREGORIAN,
            $this->month->getValue(),
            $this->year->toInteger()
        );
    }

    /**
     * Returns an integer representation of this YearMonth.
     *
     * This allows the comparison of two YearMonth instances.
     *
     * @return int
     */
    public function toInteger()
    {
        return 12 * $this->year->toInteger() + $this->month->getValue();
    }
}
