<?php

namespace Brick\DateTime;

use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\Parser\DateTimeParser;
use Brick\DateTime\Utility\Cast;

/**
 * Represents the combination of a year and a month.
 */
class YearMonth
{
    /**
     * The year, from MIN_YEAR to MAX_YEAR.
     *
     * @var integer
     */
    private $year;

    /**
     * The month, from 1 to 12.
     *
     * @var integer
     */
    private $month;

    /**
     * Class constructor.
     *
     * @param integer $year  The year, validated as an integer from MIN_YEAR to MAX_YEAR.
     * @param integer $month The month, validated as an integer in the range 1 to 12.
     */
    private function __construct($year, $month)
    {
        $this->year  = $year;
        $this->month = $month;
    }

    /**
     * Obtains an instance of `YearMonth` from a year and month.
     *
     * @param integer $year  The year, from MIN_YEAR to MAX_YEAR.
     * @param integer $month The month-of-year, from 1 (January) to 12 (December).
     *
     * @return YearMonth
     *
     * @throws DateTimeException
     */
    public static function of($year, $month)
    {
        $year  = Cast::toInteger($year);
        $month = Cast::toInteger($month);

        Field\Year::check($year);
        Field\MonthOfYear::check($month);

        return new YearMonth($year, $month);
    }

    /**
     * @param Parser\DateTimeParseResult $result
     *
     * @return YearMonth
     *
     * @throws DateTimeException If the date is not valid.
     */
    public static function from(Parser\DateTimeParseResult $result)
    {
        return YearMonth::of(
            $result->getField(Field\DateTimeField::YEAR),
            $result->getField(Field\DateTimeField::MONTH_OF_YEAR)
        );
    }

    /**
     * Obtains an instance of `YearMonth` from a text string.
     *
     * @param string              $text   The text to parse, such as `2007-12`.
     * @param DateTimeParser|null $parser The parser to use, defaults to the ISO 8601 parser.
     *
     * @return YearMonth
     *
     * @throws DateTimeException      If the date is not valid.
     * @throws DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = Parser\DateTimeParsers::isoYearMonth();
        }

        return YearMonth::from($parser->parse($text));
    }

    /**
     * Returns the current year-month in the given time-zone.
     *
     * @param TimeZone $timeZone
     *
     * @return YearMonth
     */
    public static function now(TimeZone $timeZone)
    {
        $localDate = LocalDate::now($timeZone);

        return new YearMonth($localDate->getYear(), $localDate->getMonth());
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Returns whether the year is a leap year.
     *
     * @return boolean
     */
    public function isLeapYear()
    {
        return Year::of($this->year)->isLeap();
    }

    /**
     * Returns the length of the month in days, taking account of the year.
     *
     * @return integer
     */
    public function getLengthOfMonth()
    {
        return Month::of($this->month)->getLength($this->isLeapYear());
    }

    /**
     * Returns the length of the year in days, either 365 or 366.
     *
     * @return integer
     */
    public function getLengthOfYear()
    {
        return $this->isLeapYear() ? 366: 365;
    }

    /**
     * @param YearMonth $that
     *
     * @return integer [-1,0,1] If this year-month is before, on, or after the given year-month.
     */
    public function compareTo(YearMonth $that)
    {
        if ($this->year < $that->year) {
            return -1;
        }
        if ($this->year > $that->year) {
            return 1;
        }
        if ($this->month < $that->month) {
            return -1;
        }
        if ($this->month > $that->month) {
            return 1;
        }

        return 0;
    }

    /**
     * @param YearMonth $that
     *
     * @return boolean
     */
    public function isEqualTo(YearMonth $that)
    {
        return $this->compareTo($that) === 0;
    }

    /**
     * @param YearMonth $that
     *
     * @return boolean
     */
    public function isBefore(YearMonth $that)
    {
        return $this->compareTo($that) === -1;
    }

    /**
     * @param YearMonth $that
     *
     * @return boolean
     */
    public function isAfter(YearMonth $that)
    {
        return $this->compareTo($that) === 1;
    }

    /**
     * Returns a copy of this YearMonth with the year altered.
     *
     * @param integer $year
     *
     * @return YearMonth
     */
    public function withYear($year)
    {
        if ($year == $this->year) {
            return $this;
        }

        return YearMonth::of($year, $this->month);
    }

    /**
     * Returns a copy of this YearMonth with the month-of-year altered.
     *
     * @param integer $month
     *
     * @return YearMonth
     */
    public function withMonth($month)
    {
        if ($month == $this->month) {
            return $this;
        }

        return YearMonth::of($this->year, $month);
    }

    /**
     * Combines this year-month with a day-of-month to create a LocalDate.
     *
     * @param integer $day
     *
     * @return LocalDate
     */
    public function atDay($day)
    {
        return LocalDate::of($this->year, $this->month, $day);
    }

    /**
     * Returns the ISO 8601 representation of this YearMonth.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%02u-%02u', $this->year, $this->month);
    }
}
