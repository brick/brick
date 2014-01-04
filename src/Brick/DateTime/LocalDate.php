<?php

namespace Brick\DateTime;

use Brick\Math\Math;
use Brick\Type\Cast;
use Brick\Locale\Locale;

/**
 * A date without a time-zone in the ISO-8601 calendar system, such as `2007-12-03`.
 *
 * This class is immutable.
 */
class LocalDate
{
    /**
     * The minimum supported year for instances of `LocalDate`, -999,999,999.
     */
    const MIN_YEAR = -999999999;

    /**
     * The maximum supported year for instances of `LocalDate`, 999,999,999.
     */
    const MAX_YEAR = 999999999;

    /**
     * The number of days from year zero to year 1970.
     */
    const DAYS_0000_TO_1970 = 719528;

    /**
     * The number of days in a 400 year cycle.
     */
    const DAYS_PER_CYCLE = 146097;

    /**
     * The year.
     *
     * @var int
     */
    private $year;

    /**
     * The month-of-year.
     *
     * @var int
     */
    private $month;

    /**
     * The day-of-month.
     *
     * @var int
     */
    private $day;

    /**
     * Private constructor. Use of() to obtain an instance.
     *
     * @param int $year  The year, validated as an integer from MIN_YEAR to MAX_YEAR.
     * @param int $month The month-of-year, validated as an integer from 1 to 12.
     * @param int $day   The day-of-month, validated as an integer from 1 to 31, valid for the year-month.
     */
    private function __construct($year, $month, $day)
    {
        $this->year  = $year;
        $this->month = $month;
        $this->day   = $day;
    }

    /**
     * Obtains an instance of `LocalDate` from a year, month and day.
     *
     * @param int $year  The year, from MIN_YEAR to MAX_YEAR.
     * @param int $month The month-of-year, from 1 (January) to 12 (December).
     * @param int $day   The day-of-month, from 1 to 31.
     *
     * @return LocalDate
     *
     * @throws DateTimeException
     * @throws \InvalidArgumentException
     */
    public static function of($year, $month, $day)
    {
        $year = Cast::toInteger($year);
        $month = Cast::toInteger($month);
        $day = Cast::toInteger($day);

        self::checkYear($year);

        if ($month < 1 || $month > 12) {
            throw new DateTimeException('Month must be in the interval [1, 12]');
        }

        $daysInMonth = Month::of($month)->getLength(Year::of($year)->isLeap());

        if ($day < 1 || $day > $daysInMonth) {
            throw new DateTimeException(sprintf('Day must be in the interval [1, %d]', $daysInMonth));
        }

        return new LocalDate($year, $month, $day);
    }

    /**
     * @todo was private (expects a validated integer, but was needed for YearMonth as well) - fix that
     *
     * @param int $year The year to check, validated as an integer.
     * @return void
     * @throws DateTimeException
     */
    public static function checkYear($year)
    {
        if ($year < Year::MIN_YEAR || $year > Year::MAX_YEAR) {
            throw new DateTimeException(sprintf(
                'Year must be in the interval [%d, %d]',
                Year::MIN_YEAR,
                Year::MAX_YEAR
            ));
        }
    }

    /**
     * @param int $year      The year, validated as an integer, and valid.
     * @param int $dayOfYear The day of year, validated as an integer.
     * @return void
     * @throws DateTimeException
     */
    private static function checkDayOfYear($year, $dayOfYear)
    {
        $daysInYear = (new Year($year))->getLength();

        if ($dayOfYear < 1 || $dayOfYear > $daysInYear) {
            throw new DateTimeException(sprintf('Day of year must be in the interval [1, %d]', $daysInYear));
        }
    }

    /**
     * Obtains an instance of `LocalDate` from a year and day-of-year.
     *
     * @param int $year      The year, from MIN_YEAR to MAX_YEAR.
     * @param int $dayOfYear The day-of-year, from 1 to 366.
     *
     * @return LocalDate
     */
    public static function ofYearDay($year, $dayOfYear)
    {
        $year = Cast::toInteger($year);
        $dayOfYear = Cast::toInteger($dayOfYear);

        self::checkYear($year);
        self::checkDayOfYear($year, $dayOfYear);

        $leap = (new Year($year))->isLeap();

        $monthOfYear = Month::of(Math::intDiv($dayOfYear - 1, 31) + 1);
        $monthEnd = $monthOfYear->firstDayOfYear($leap) + $monthOfYear->getLength($leap) - 1;

        if ($dayOfYear > $monthEnd) {
            $monthOfYear = $monthOfYear->plus(1);
        }

        $dayOfMonth = $dayOfYear - $monthOfYear->firstDayOfYear($leap) + 1;

        return LocalDate::of($year, $monthOfYear->getValue(), $dayOfMonth);
    }

    /**
     * @param Parser\DateTimeParseResult $result
     * @return LocalDate
     * @throws DateTimeException If the date is not valid.
     */
    public static function from(Parser\DateTimeParseResult $result)
    {
        return LocalDate::of(
            $result->getField(Field\DateTimeField::YEAR),
            $result->getField(Field\DateTimeField::MONTH_OF_YEAR),
            $result->getField(Field\DateTimeField::DAY_OF_MONTH)
        );
    }

    /**
     * Obtains an instance of `LocalDate` from a text string.
     *
     * @param string                     $text   The text to parse, such as `2007-12-03`.
     * @param Parser\DateTimeParser|null $parser The parser to use. Defaults to the ISO 8601 parser.
     *
     * @return LocalDate
     *
     * @throws DateTimeException             If the date is not valid.
     * @throws Parser\DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, Parser\DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = Parser\DateTimeParsers::isoLocalDate();
        }

        return LocalDate::from($parser->parse($text));
    }

    /**
     * Obtains an instance of `LocalDate` from the epoch day count.
     *
     * The Epoch Day count is a simple incrementing count of days
     * where day 0 is 1970-01-01. Negative numbers represent earlier days.
     *
     * @param int $epochDay
     * @return LocalDate
     */
    public static function ofEpochDay($epochDay)
    {
        $zeroDay = $epochDay + self::DAYS_0000_TO_1970;
        // Find the march-based year.
        $zeroDay -= 60; // Adjust to 0000-03-01 so leap day is at end of four year cycle.
        $adjust = 0;
        if ($zeroDay < 0) {
            // Adjust negative years to positive for calculation.
            $adjustCycles = Math::intDiv(($zeroDay + 1), self::DAYS_PER_CYCLE) - 1;
            $adjust = $adjustCycles * 400;
            $zeroDay += -$adjustCycles * self::DAYS_PER_CYCLE;
        }
        $yearEst = Math::intDiv(400 * $zeroDay + 591, self::DAYS_PER_CYCLE);
        $doyEst = $zeroDay - (365 * $yearEst + Math::intDiv($yearEst, 4) - Math::intDiv($yearEst, 100) + Math::intDiv($yearEst, 400));
        if ($doyEst < 0) {
            // Fix estimate.
            $yearEst--;
            $doyEst = $zeroDay - (365 * $yearEst + Math::intDiv($yearEst, 4) - Math::intDiv($yearEst, 100) + Math::intDiv($yearEst, 400));
        }
        $yearEst += $adjust; // Reset any negative year.
        $marchDoy0 = $doyEst;

        // Convert march-based values back to January-based.
        $marchMonth0 = Math::intDiv($marchDoy0 * 5 + 2, 153);
        $month = ($marchMonth0 + 2) % 12 + 1;
        $dom = $marchDoy0 - Math::intDiv($marchMonth0 * 306 + 5, 10) + 1;
        $yearEst += Math::intDiv($marchMonth0, 10);

        // Check year now we are certain it is correct.
        self::checkYear($yearEst);

        return new LocalDate($yearEst, $month, $dom);
    }

    /**
     * Returns the current date, in the given time zone.
     *
     * @todo alias for today(), check which one to keep.
     *
     * @param TimeZone $timeZone
     * @return LocalDate
     */
    public static function now(TimeZone $timeZone)
    {
        return ZonedDateTime::now($timeZone)->getDate();
    }

    /**
     * Returns today's Date, in the given timezone.
     *
     * @param TimeZone $timeZone
     * @return LocalDate
     */
    public static function today(TimeZone $timeZone)
    {
        return ZonedDateTime::now($timeZone)->getDate();
    }

    /**
     * @return LocalDate
     */
    public static function minDate()
    {
        return LocalDate::of(self::MIN_YEAR, 1, 1);
    }

    /**
     * @return LocalDate
     */
    public static function maxDate()
    {
        return LocalDate::of(self::MAX_YEAR, 12, 31);
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return Weekday
     */
    public function getDayOfWeek()
    {
        return Weekday::of(Math::floorMod($this->toEpochDay() + 3, 7) + 1);
    }

    /**
     * Resolves the date, resolving days past the end of month.
     *
     * @todo correct behaviour? or add the extra days to the next month? or throw exception?
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return LocalDate
     */
    private function resolvePreviousValid($year, $month, $day)
    {
        $daysInMonth = 31; // @todo

        $day = min($day, $daysInMonth);

        return new LocalDate($year, $month, $day);
    }

    /**
     * Returns a copy of this LocalDate with the year altered.
     *
     * @param int $year
     * @return LocalDate
     */
    public function withYear($year)
    {
        if ($year == $this->year) {
            return $this;
        }

        return $this->resolvePreviousValid($year, $this->month, $this->day);
    }

    /**
     * Returns a copy of this LocalDate with the month-of-year altered.
     *
     * @param int $month
     * @return LocalDate
     */
    public function withMonth($month)
    {
        if ($month == $this->month) {
            return $this;
        }

        return $this->resolvePreviousValid($this->year, $month, $this->day);
    }

    /**
     * Returns a copy of this LocalDate with the day-of-month altered.
     *
     * @param int $day
     * @return LocalDate
     */
    public function withDay($day)
    {
        if ($day == $this->day) {
            return $this;
        }

        return $this->resolvePreviousValid($this->year, $this->month, $day);
    }

    /**
     * Returns a copy of this LocalDate with the specified period in years added.
     *
     * @param int $yearsToAdd
     * @return LocalDate
     */
    public function plusYears($yearsToAdd)
    {
        return $this->withYear($this->year + Cast::toInteger($yearsToAdd));
    }

    /**
     * Returns a copy of this LocalDate with the specified period in months added.
     *
     * @param int $monthsToAdd
     * @return LocalDate
     */
    public function plusMonths($monthsToAdd)
    {
        $monthCount = $this->year * 12 + ($this->month - 1);
        $calcMonths = $monthCount + $monthsToAdd;

        // @todo
    }

    /**
     * Returns a copy of this LocalDate with the specified period in weeks added.
     *
     * @param int $weeksToAdd
     * @return LocalDate
     */
    public function plusWeeks($weeksToAdd)
    {
        return $this->plusDays($weeksToAdd * 7);
    }

    /**
     * Returns a copy of this LocalDate with the specified period in days added.
     *
     * @param int $daysToAdd
     * @return LocalDate
     */
    public function plusDays($daysToAdd)
    {
        return LocalDate::ofEpochDay($this->toEpochDay() + Cast::toInteger($daysToAdd));
    }

    /**
     * Returns a copy of this LocalDate with the specified period in years subtracted.
     *
     * @param int $yearsToSubtract
     * @return LocalDate
     */
    public function minusYears($yearsToSubtract)
    {
        return $this->plusYears(- $yearsToSubtract);
    }

    /**
     * Returns a copy of this LocalDate with the specified period in months subtracted.
     *
     * @param int $monthsToSubtract
     * @return LocalDate
     */
    public function minusMonths($monthsToSubtract)
    {
        return $this->plusMonths(- $monthsToSubtract);
    }

    /**
     * Returns a copy of this LocalDate with the specified period in weeks subtracted.
     *
     * @param int $weeksToSubtract
     * @return LocalDate
     */
    public function minusWeeks($weeksToSubtract)
    {
        return $this->plusWeeks(- $weeksToSubtract);
    }

    /**
     * Returns a copy of this LocalDate with the specified period in days subtracted.
     *
     * @param int $daysToSubtract
     * @return LocalDate
     */
    public function minusDays($daysToSubtract)
    {
        return $this->plusDays(- $daysToSubtract);
    }

    /**
     * Returns -1 if this date is before the given date, 1 if after, 0 if the dates are equal.
     *
     * @param LocalDate $date
     * @return int
     */
    public function compareTo(LocalDate $date)
    {
        if ($this->year < $date->year) {
            return -1;
        }
        if ($this->year > $date->year) {
            return 1;
        }
        if ($this->month < $date->month) {
            return -1;
        }
        if ($this->month > $date->month) {
            return 1;
        }
        if ($this->day < $date->day) {
            return -1;
        }
        if ($this->day > $date->day) {
            return 1;
        }

        return 0;
    }

    /**
     * @param LocalDate $date
     * @return bool
     */
    public function isEqualTo(LocalDate $date)
    {
        return $this->compareTo($date) == 0;
    }

    /**
     * @param LocalDate $date
     * @return bool
     */
    public function isLessThan(LocalDate $date)
    {
        return $this->compareTo($date) < 0;
    }

    /**
     * @param LocalDate $date
     * @return bool
     */
    public function isLessThanOrEqualTo(LocalDate $date)
    {
        return $this->compareTo($date) <= 0;
    }

    /**
     * @param LocalDate $date
     * @return bool
     */
    public function isGreaterThan(LocalDate $date)
    {
        return $this->compareTo($date) > 0;
    }

    /**
     * @param LocalDate $date
     * @return bool
     */
    public function isGreaterThanOrEqualTo(LocalDate $date)
    {
        return $this->compareTo($date) >= 0;
    }

    /**
     * Returns whether this date is between the given dates, inclusive.
     *
     * @param LocalDate $first The first date.
     * @param LocalDate $last  The last date.
     * @return bool
     */
    public function isBetweenInclusive(LocalDate $first, LocalDate $last)
    {
        return $this->isGreaterThanOrEqualTo($first) && $this->isLessThanOrEqualTo($last);
    }

    /**
     * Returns whether this date is between the given dates, exclusive.
     *
     * @param LocalDate $first The first date.
     * @param LocalDate $last  The last date.
     * @return bool
     */
    public function isBetweenExclusive(LocalDate $first, LocalDate $last)
    {
        return $this->isGreaterThan($first) && $this->isLessThan($last);
    }

    /**
     * Returns a local date-time formed from this date at the specified time.
     *
     * @param LocalTime $time
     * @return LocalDateTime
     */
    public function atTime(LocalTime $time)
    {
        return LocalDateTime::ofDateTime($this, $time);
    }

    /**
     * @param \DateTime $dateTime
     * @return void
     * @deprecated
     */
    public function applyToDateTime(\DateTime $dateTime)
    {
        $dateTime->setDate($this->year, $this->month, $this->day);
    }

    /**
     * Returns the number of days since the 1st January of year zero.
     *
     * @return int
     */
    public function toInteger()
    {
        $y = $this->year;
        $m = $this->month;

        $total = 365 * $y;

        if ($y >= 0) {
            $total += Math::intDiv($y + 3, 4) - Math::intDiv($y + 99, 100) + Math::intDiv($y + 399, 400);
        } else {
            $total -= Math::intDiv($y, -4) - Math::intDiv($y, -100) + Math::intDiv($y, -400);
        }

        $total += Math::intDiv(367 * $m - 362, 12);
        $total += $this->day - 1;

        if ($m > 2) {
            $total--;
            if (! $this->isLeapYear()) {
                $total--;
            }
        }

        return $total;
    }

    /**
     * Checks if the year is a leap year, according to the ISO proleptic calendar system rules.
     *
     * @return bool
     */
    public function isLeapYear()
    {
        return Year::of($this->year)->isLeap();
    }

    /**
     * Returns the number of days since the UNIX epoch of 1st January 1970.
     *
     * @return int
     */
    public function toEpochDay()
    {
        return $this->toInteger() - self::DAYS_0000_TO_1970;
    }

    /**
     * Returns the ISO 8601 representation of this LocalDate.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf('%02u-%02u-%02u', $this->year, $this->month, $this->day);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param \Brick\Locale\Locale $locale
     * @return string
     */
    public function format(Locale $locale)
    {
        $formatter = new \IntlDateFormatter((string) $locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
        $formatter->setTimeZone('UTC');

        $datetime = new \DateTime(null, new \DateTimeZone('UTC'));
        $this->applyToDateTime($datetime);

        return $formatter->format($datetime);
    }
}
