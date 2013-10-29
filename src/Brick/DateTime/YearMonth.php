<?php

namespace Brick\DateTime;

use Brick\Type\Cast;

/**
 * Represents the combination of a year and a month.
 */
class YearMonth
{
    /**
     * @var integer
     */
    private $year;

    /**
     * @var integer
     */
    private $month;

    /**
     * Class constructor.
     *
     * @param int $year  The year, validated as an integer in the valid range.
     * @param int $month The month, validated as an integer in the valid range.
     */
    private function __construct($year, $month)
    {
        $this->year  = $year;
        $this->month = $month;
    }

    /**
     * Obtains an instance of `YearMonth` from a year and month.
     *
     * @param int $year  The year, from MIN_YEAR to MAX_YEAR.
     * @param int $month The month-of-year, from 1 (January) to 12 (December).
     *
     * @return YearMonth
     *
     * @throws DateTimeException
     */
    public static function of($year, $month)
    {
        $year = Cast::toInteger($year);
        $month = Cast::toInteger($month);

        LocalDate::checkYear($year);

        if ($month < 1 || $month > 12) {
            throw new DateTimeException('Month must be in the interval [1, 12]');
        }

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
     * @param string                     $text   The text to parse, such as `2007-12`.
     * @param Parser\DateTimeParser|null $parser The parser to use. Defaults to the ISO 8601 parser.
     *
     * @return YearMonth
     *
     * @throws DateTimeException             If the date is not valid.
     * @throws Parser\DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, Parser\DateTimeParser $parser = null)
    {
        $parser = $parser ?: Parser\DateTimeParsers::isoYearMonth();

        return YearMonth::from($parser->parse($text));
    }

    /**
     * @param TimeZone $timezone
     * @return YearMonth
     */
    public static function now(TimeZone $timezone)
    {
        $localDate = LocalDate::now($timezone);

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
     * Returns the number of days in this YearMonth.
     *
     * @return integer [28-31]
     */
    public function getNumberOfDays()
    {
        return cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    }

    /**
     * Returns an integer representation of this YearMonth.
     *
     * This allows the comparison of two YearMonth instances.
     *
     * @return integer
     */
    public function toInteger()
    {
        return 12 * $this->year + $this->month;
    }

    /**
     * Returns the ISO 8601 representation of this YearMonth.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf('%02u-%02u', $this->year, $this->month);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
