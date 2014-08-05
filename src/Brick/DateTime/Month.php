<?php

namespace Brick\DateTime;

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
     * The English names of the months.
     *
     * @var array
     */
    private static $names = [
        1 => 'January',
             'February',
             'March',
             'April',
             'May',
             'June',
             'July',
             'August',
             'September',
             'October',
             'November',
             'December'
    ];

    /**
     * Cache of all the months.
     *
     * @var Month[]
     */
    private static $values = [];

    /**
     * The month number, 1 (January) to 12 (December).
     *
     * @var integer
     */
    private $month;

    /**
     * Private constructor. Use of() to get a Month instance.
     *
     * @param integer $month
     *
     * @throws \UnexpectedValueException
     */
    private function __construct($month)
    {
        $this->month = $month;
    }

    /**
     * Returns an instance of Month for the given month value.
     *
     * @param integer $month The day month number, from 1 (January) to 12 (December).
     *
     * @return Month The Month instance.
     *
     * @throws \UnexpectedValueException
     */
    public static function of($month)
    {
        $month = filter_var($month, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => self::JANUARY,
                'max_range' => self::DECEMBER
            ]
        ]);

        if (is_int($month)) {
            if (! isset(self::$values[$month])) {
                self::$values[$month] = new Month($month);
            }

            return self::$values[$month];
        }

        throw new \UnexpectedValueException('Month must be an integer in the range 1 to 12.');
    }

    /**
     * Returns the twelve months of the year in an array.
     *
     * @return Month[]
     */
    public static function getMonths()
    {
        $months = [];

        for ($month = Month::JANUARY; $month <= Month::DECEMBER; $month++) {
            $months[] = Month::of($month);
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
        return Month::of(Month::JANUARY);
    }

    /**
     * Returns a Month representing February.
     *
     * @return Month
     */
    public static function february()
    {
        return Month::of(Month::FEBRUARY);
    }

    /**
     * Returns a Month representing March.
     *
     * @return Month
     */
    public static function march()
    {
        return Month::of(Month::MARCH);
    }

    /**
     * Returns a Month representing April.
     *
     * @return Month
     */
    public static function april()
    {
        return Month::of(Month::APRIL);
    }

    /**
     * Returns a Month representing May.
     *
     * @return Month
     */
    public static function may()
    {
        return Month::of(Month::MAY);
    }

    /**
     * Returns a Month representing June.
     *
     * @return Month
     */
    public static function june()
    {
        return Month::of(Month::JUNE);
    }

    /**
     * Returns a Month representing July.
     *
     * @return Month
     */
    public static function july()
    {
        return Month::of(Month::JULY);
    }

    /**
     * Returns a Month representing August.
     *
     * @return Month
     */
    public static function august()
    {
        return Month::of(Month::AUGUST);
    }

    /**
     * Returns a Month representing September.
     *
     * @return Month
     */
    public static function september()
    {
        return Month::of(Month::SEPTEMBER);
    }

    /**
     * Returns a Month representing October.
     *
     * @return Month
     */
    public static function october()
    {
        return Month::of(Month::OCTOBER);
    }

    /**
     * Returns a Month representing November.
     *
     * @return Month
     */
    public static function november()
    {
        return Month::of(Month::NOVEMBER);
    }

    /**
     * Returns a Month representing December.
     *
     * @return Month
     */
    public static function december()
    {
        return Month::of(Month::DECEMBER);
    }

    /**
     * Returns whether this Month equals another Month.
     *
     * @param Month $other
     *
     * @return boolean
     */
    public function isEqualTo(Month $other)
    {
        return ($this->month == $other->month);
    }

    /**
     * Gets the day-of-year for the first day of this month.
     *
     * This returns the day-of-year that this month begins on, using the leap
     * year flag to determine the length of February.
     *
     * @param boolean $leapYear
     *
     * @return integer
     */
    public function firstDayOfYear($leapYear)
    {
        $leap = $leapYear ? 1 : 0;

        switch ($this->month) {
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
     * Gets the length of this month in days.
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
        switch ($this->month) {
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
        return Month::of((((($this->month - 1 + $months) % 12) + 12) % 12) + 1);
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
     * Returns the ISO-8601 month number.
     *
     * @return integer The month number, from 1 (January) to 12 (December).
     */
    public function getValue()
    {
        return $this->month;
    }

    /**
     * Returns the English name of this Month.
     *
     * The first letter is uppercase, the following letters are lowercase.
     *
     * @return string
     */
    public function toString()
    {
        return self::$names[$this->month];
    }

    /**
     * @return string
     */
    public function  __toString()
    {
        return $this->toString();
    }
}
