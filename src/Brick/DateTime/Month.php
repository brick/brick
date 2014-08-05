<?php

namespace Brick\DateTime;

use Brick\Type\Cast;

/**
 * Represents a month.
 */
class Month
{
    const JANUARY   = 1;
    const FEBRUARY  = 2;
    const MARCH     = 3;
    const APRIL     = 4;
    const MAY       = 5;
    const JUNE      = 6;
    const JULY      = 7;
    const AUGUST    = 8;
    const SEPTEMBER = 9;
    const OCTOBER   = 10;
    const NOVEMBER  = 11;
    const DECEMBER  = 12;

    /**
     * The month number, from 1 (January) to 12 (December).
     *
     * @var integer
     */
    private $value;

    /**
     * Private constructor. Use of() to get a Month instance.
     *
     * @param integer $month The month value, validated.
     */
    private function __construct($month)
    {
        $this->value = $month;
    }

    /**
     * Returns a cached Month instance.
     *
     * @param integer $value The month value, validated.
     *
     * @return Month
     */
    private function get($value)
    {
        static $values;

        if (! isset($values[$value])) {
            $values[$value] = new Month($value);
        }

        return $values[$value];
    }

    /**
     * Returns an instance of Month for the given month value.
     *
     * @param integer $value The month number, from 1 (January) to 12 (December).
     *
     * @return Month The Month instance.
     *
     * @throws \InvalidArgumentException
     */
    public static function of($value)
    {
        $value = Cast::toInteger($value);

        if ($value < Month::JANUARY || $value > Month::DECEMBER) {
            throw new \InvalidArgumentException('The month must be in range 1 to 12.');
        }

        return Month::get($value);
    }

    /**
     * Returns the twelve months of the year in an array.
     *
     * @return Month[]
     */
    public static function getAll()
    {
        $months = [];

        for ($month = Month::JANUARY; $month <= Month::DECEMBER; $month++) {
            $months[] = Month::get($month);
        }

        return $months;
    }

    /**
     * Returns a Month representing January.
     *
     * @return Month
     */
    public static function january()
    {
        return Month::get(Month::JANUARY);
    }

    /**
     * Returns a Month representing February.
     *
     * @return Month
     */
    public static function february()
    {
        return Month::get(Month::FEBRUARY);
    }

    /**
     * Returns a Month representing March.
     *
     * @return Month
     */
    public static function march()
    {
        return Month::get(Month::MARCH);
    }

    /**
     * Returns a Month representing April.
     *
     * @return Month
     */
    public static function april()
    {
        return Month::get(Month::APRIL);
    }

    /**
     * Returns a Month representing May.
     *
     * @return Month
     */
    public static function may()
    {
        return Month::get(Month::MAY);
    }

    /**
     * Returns a Month representing June.
     *
     * @return Month
     */
    public static function june()
    {
        return Month::get(Month::JUNE);
    }

    /**
     * Returns a Month representing July.
     *
     * @return Month
     */
    public static function july()
    {
        return Month::get(Month::JULY);
    }

    /**
     * Returns a Month representing August.
     *
     * @return Month
     */
    public static function august()
    {
        return Month::get(Month::AUGUST);
    }

    /**
     * Returns a Month representing September.
     *
     * @return Month
     */
    public static function september()
    {
        return Month::get(Month::SEPTEMBER);
    }

    /**
     * Returns a Month representing October.
     *
     * @return Month
     */
    public static function october()
    {
        return Month::get(Month::OCTOBER);
    }

    /**
     * Returns a Month representing November.
     *
     * @return Month
     */
    public static function november()
    {
        return Month::get(Month::NOVEMBER);
    }

    /**
     * Returns a Month representing December.
     *
     * @return Month
     */
    public static function december()
    {
        return Month::get(Month::DECEMBER);
    }

    /**
     * Returns the ISO-8601 month number.
     *
     * @return integer The month number, from 1 (January) to 12 (December).
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns whether this Month equals another Month.
     *
     * @param Month $that
     *
     * @return boolean
     */
    public function isEqualTo(Month $that)
    {
        return ($this->value === $that->value);
    }

    /**
     * Returns the day-of-year for the first day of this month.
     *
     * This returns the day-of-year that this month begins on, using the leap
     * year flag to determine the length of February.
     *
     * @param boolean $leapYear
     *
     * @return integer
     */
    public function getFirstDayOfYear($leapYear)
    {
        $leap = $leapYear ? 1 : 0;

        switch ($this->value) {
            case Month::JANUARY:
                return 1;
            case Month::FEBRUARY:
                return 32;
            case Month::MARCH:
                return 60 + $leap;
            case Month::APRIL:
                return 91 + $leap;
            case Month::MAY:
                return 121 + $leap;
            case Month::JUNE:
                return 152 + $leap;
            case Month::JULY:
                return 182 + $leap;
            case Month::AUGUST:
                return 213 + $leap;
            case Month::SEPTEMBER:
                return 244 + $leap;
            case Month::OCTOBER:
                return 274 + $leap;
            case Month::NOVEMBER:
                return 305 + $leap;
            case Month::DECEMBER:
            default:
                return 335 + $leap;
        }
    }

    /**
     * Returns the length of this month in days.
     *
     * This takes a flag to determine whether to return the length for a leap year or not.
     *
     * February has 28 days in a standard year and 29 days in a leap year.
     * April, June, September and November have 30 days.
     * All other months have 31 days.
     *
     * @param boolean $leapYear
     *
     * @return integer
     */
    public function getLength($leapYear)
    {
        switch ($this->value) {
            case Month::FEBRUARY:
                return ($leapYear ? 29 : 28);
            case Month::APRIL:
            case Month::JUNE:
            case Month::SEPTEMBER:
            case Month::NOVEMBER:
                return 30;
            default:
                return 31;
        }
    }

    /**
     * Returns the month that is the specified number of months after this one.
     *
     * The calculation rolls around the end of the year from December to January.
     * The specified period may be negative.
     *
     * @param integer $months
     *
     * @return Month
     */
    public function plus($months)
    {
        return Month::get((((($this->value - 1 + $months) % 12) + 12) % 12) + 1);
    }

    /**
     * Returns the month that is the specified number of months before this one.
     *
     * The calculation rolls around the start of the year from January to December.
     * The specified period may be negative.
     *
     * @param integer $months
     *
     * @return Month
     */
    public function minus($months)
    {
        return $this->plus(- $months);
    }

    /**
     * Returns the capitalized English name of this Month.
     *
     * @return string
     */
    public function __toString()
    {
        return [
            1 => 'JANUARY',
            2 => 'FEBRUARY',
            3 => 'MARCH',
            4 => 'APRIL',
            5 => 'MAY',
            6 => 'JUNE',
            7 => 'JULY',
            8 => 'AUGUST',
            9 => 'SEPTEMBER',
            10 => 'OCTOBER',
            11 => 'NOVEMBER',
            12 => 'DECEMBER'
        ][$this->value];
    }
}
