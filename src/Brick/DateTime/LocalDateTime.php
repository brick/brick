<?php

namespace Brick\DateTime;

use Brick\DateTime\Utility\Math;
use Brick\Type\Cast;
use Brick\Locale\Locale;

/**
 * A date-time without a time-zone in the ISO-8601 calendar system, such as 2007-12-03T10:15:30.
 *
 * This class is immutable.
 */
class LocalDateTime
{
    /**
     * @var LocalDate
     */
    private $date;

    /**
     * @var LocalTime
     */
    private $time;

    /**
     * Private constructor. Use of() to obtain an instance.
     *
     * @param LocalDate $date
     * @param LocalTime $time
     */
    private function __construct(LocalDate $date, LocalTime $time)
    {
        $this->date = $date;
        $this->time = $time;
    }

    /**
     * @param LocalDate $date
     * @param LocalTime $time
     *
     * @return LocalDateTime
     */
    public static function ofDateTime(LocalDate $date, LocalTime $time)
    {
        return new LocalDateTime($date, $time);
    }

    /**
     * @param integer $year
     * @param integer $month
     * @param integer $day
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     *
     * @return LocalDateTime
     */
    public static function of($year, $month, $day, $hour, $minute, $second = 0)
    {
        return new LocalDateTime(
            LocalDate::of($year, $month, $day),
            LocalTime::of($hour, $minute, $second)
        );
    }

    /**
     * @param TimeZone $timeZone
     *
     * @return LocalDateTime
     */
    public static function now(TimeZone $timeZone)
    {
        return ZonedDateTime::now($timeZone)->getDateTime();
    }

    /**
     * @param Parser\DateTimeParseResult $result
     *
     * @return LocalDateTime
     *
     * @throws DateTimeException If the date-time is not valid.
     */
    public static function from(Parser\DateTimeParseResult $result)
    {
        return new LocalDateTime(
            LocalDate::from($result),
            LocalTime::from($result)
        );
    }

    /**
     * Obtains an instance of `LocalDateTime` from a text string.
     *
     * @param string                     $text   The text to parse, such as `2007-12-03T10:15:30`.
     * @param Parser\DateTimeParser|null $parser The parser to use. Defaults to the ISO 8601 parser.
     *
     * @return LocalDateTime
     *
     * @throws DateTimeException             If the date-time is not valid.
     * @throws Parser\DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, Parser\DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = Parser\DateTimeParsers::isoLocalDateTime();
        }

        return LocalDateTime::from($parser->parse($text));
    }

    /**
     * @return LocalDate
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return LocalTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return integer
     */
    public function getYear()
    {
        return $this->date->getYear();
    }

    /**
     * @return integer
     */
    public function getMonth()
    {
        return $this->date->getMonth();
    }

    /**
     * @return integer
     */
    public function getDay()
    {
        return $this->date->getDay();
    }

    /**
     * @return Weekday
     */
    public function getDayOfWeek()
    {
        return $this->date->getDayOfWeek();
    }

    /**
     * @return integer
     */
    public function getHour()
    {
        return $this->time->getHour();
    }

    /**
     * @return integer
     */
    public function getMinute()
    {
        return $this->time->getMinute();
    }

    /**
     * @return integer
     */
    public function getSecond()
    {
        return $this->time->getSecond();
    }

    /**
     * Returns a copy of this date-time with the new date and time, checking
     * to see if a new object is in fact required.
     *
     * @param LocalDate $date
     * @param LocalTime $time
     *
     * @return LocalDateTime
     */
    private function with(LocalDate $date, LocalTime $time)
    {
        if ($date->isEqualTo($this->date) && $time->isEqualTo($this->time)) {
            return $this;
        }

        return new LocalDateTime($date, $time);
    }

    /**
     * Returns a copy of this LocalDateTime with the date altered.
     *
     * @param LocalDate $date
     *
     * @return LocalDateTime
     */
    public function withDate(LocalDate $date)
    {
        return $this->with($date, $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the time altered.
     *
     * @param LocalTime $time
     *
     * @return LocalDateTime
     */
    public function withTime(LocalTime $time)
    {
        return $this->with($this->date, $time);
    }

    /**
     * Returns a copy of this LocalDateTime with the year altered.
     *
     * @param integer $year
     *
     * @return LocalDateTime
     */
    public function withYear($year)
    {
        return $this->with($this->date->withYear($year), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the month-of-year altered.
     *
     * @param integer $month
     *
     * @return LocalDateTime
     */
    public function withMonth($month)
    {
        return $this->with($this->date->withMonth($month), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the day-of-month altered.
     *
     * @param integer $day
     *
     * @return LocalDateTime
     */
    public function withDay($day)
    {
        return $this->with($this->date->withDay($day), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the hour-of-day altered.
     *
     * @param integer $hour
     *
     * @return LocalDateTime
     */
    public function withHour($hour)
    {
        return $this->with($this->date, $this->time->withHour($hour));
    }

    /**
     * Returns a copy of this LocalDateTime with the minute-of-hour altered.
     *
     * @param integer $minute
     *
     * @return LocalDateTime
     */
    public function withMinute($minute)
    {
        return $this->with($this->date, $this->time->withMinute($minute));
    }

    /**
     * Returns a copy of this LocalDateTime with the second-of-minute altered.
     *
     * @param integer $second
     *
     * @return LocalDateTime
     */
    public function withSecond($second)
    {
        return $this->with($this->date, $this->time->withSecond($second));
    }

    /**
     * Returns a zoned date-time formed from this date-time and the specified time-zone.
     *
     * @param TimeZone $zone The zime-zone to use.
     *
     * @return ZonedDateTime The zoned date-time formed from this date-time.
     */
    public function atTimeZone(TimeZone $zone)
    {
        return ZonedDateTime::of($this, $zone);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified Period added.
     *
     * @param Period $period
     *
     * @return LocalDateTime
     */
    public function plusPeriod(Period $period)
    {
        $result = $this;

        if ($period->getYears() != 0) {
            $result = $result->plusYears($period->getYears());
        }
        if ($period->getMonths() != 0) {
            $result = $result->plusMonths($period->getMonths());
        }
        if ($period->getDays() != 0) {
            $result = $result->plusDays($period->getDays());
        }
        if ($period->getHours() != 0) {
            $result = $result->plusHours($period->getHours());
        }
        if ($period->getMinutes() != 0) {
            $result = $result->plusMinutes($period->getMinutes());
        }
        if ($period->getSeconds() != 0) {
            $result = $result->plusSeconds($period->getSeconds());
        }

        return $result;
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in years added.
     *
     * @param integer $years
     *
     * @return LocalDateTime
     */
    public function plusYears($years)
    {
        return $this->with($this->date->plusYears($years), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in months added.
     *
     * @param integer $months
     *
     * @return LocalDateTime
     */
    public function plusMonths($months)
    {
        return $this->with($this->date->plusMonths($months), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in weeks added.
     *
     * @param integer $weeks
     *
     * @return LocalDateTime
     */
    public function plusWeeks($weeks)
    {
        return $this->with($this->date->plusWeeks($weeks), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in days added.
     *
     * @param integer $days
     *
     * @return LocalDateTime
     */
    public function plusDays($days)
    {
        return $this->with($this->date->plusDays($days), $this->time);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in hours added.
     *
     * @param integer $hours
     *
     * @return LocalDateTime
     */
    public function plusHours($hours)
    {
        return $this->plusWithOverflow($hours, 0, 0, 1);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in minutes added.
     *
     * @param integer $minutes
     *
     * @return LocalDateTime
     */
    public function plusMinutes($minutes)
    {
        return $this->plusWithOverflow(0, $minutes, 0, 1);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in seconds added.
     *
     * @param integer $seconds
     *
     * @return LocalDateTime
     */
    public function plusSeconds($seconds)
    {
        return $this->plusWithOverflow(0, 0, $seconds, 1);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified Period subtracted.
     *
     * @param Period $period
     *
     * @return LocalDateTime
     */
    public function minusPeriod(Period $period)
    {
        return $this->plusPeriod($period->negated());
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in years subtracted.
     *
     * @param integer $years
     *
     * @return LocalDateTime
     */
    public function minusYears($years)
    {
        return $this->plusYears(- $years);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in months subtracted.
     *
     * @param integer $months
     *
     * @return LocalDateTime
     */
    public function minusMonths($months)
    {
        return $this->plusMonths(- $months);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in weeks subtracted.
     *
     * @param integer $weeks
     *
     * @return LocalDateTime
     */
    public function minusWeeks($weeks)
    {
        return $this->plusWeeks(- $weeks);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in days subtracted.
     *
     * @param integer $days
     *
     * @return LocalDateTime
     */
    public function minusDays($days)
    {
        return $this->plusDays(- $days);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in hours subtracted.
     *
     * @param integer $hours
     *
     * @return LocalDateTime
     */
    public function minusHours($hours)
    {
        return $this->plusWithOverflow($hours, 0, 0, -1);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in minutes subtracted.
     *
     * @param integer $minutes
     *
     * @return LocalDateTime
     */
    public function minusMinutes($minutes)
    {
        return $this->plusWithOverflow(0, $minutes, 0, -1);
    }

    /**
     * Returns a copy of this LocalDateTime with the specified period in seconds subtracted.
     *
     * @param integer $seconds
     *
     * @return LocalDateTime
     */
    public function minusSeconds($seconds)
    {
        return $this->plusWithOverflow(0, 0, $seconds, -1);
    }

    /**
     * Returns a copy of this `LocalDateTime` with the specified period added.
     *
     * @param integer $hours   The hours to add, may be negative.
     * @param integer $minutes The minutes to add, may be negative.
     * @param integer $seconds The seconds to add, may be negative.
     * @param integer $sign    The sign, `1` to add or `-1` to subtract, validated as an integer.
     *
     * @return LocalDateTime The combined result.
     */
    private function plusWithOverflow($hours, $minutes, $seconds, $sign)
    {
        $hours = Cast::toInteger($hours);
        $minutes = Cast::toInteger($minutes);
        $seconds = Cast::toInteger($seconds);

        if (($hours | $minutes | $seconds) == 0) {
            return $this;
        }

        $totDays =
            Math::div($hours, LocalTime::HOURS_PER_DAY) +
            Math::div($minutes, LocalTime::MINUTES_PER_DAY) +
            Math::div($seconds, LocalTime::SECONDS_PER_DAY);
        $totDays *= $sign;

        $totSeconds =
            ($seconds % LocalTime::SECONDS_PER_DAY) +
            ($minutes % LocalTime::MINUTES_PER_DAY) * LocalTime::SECONDS_PER_MINUTE +
            ($hours % LocalTime::HOURS_PER_DAY) * LocalTime::SECONDS_PER_HOUR;

        $curSoD = $this->time->toSecondOfDay();
        $totSeconds = $totSeconds * $sign + $curSoD;
        $totDays += Math::floorDiv($totSeconds, LocalTime::SECONDS_PER_DAY);
        $newSoD = Math::floorMod($totSeconds, LocalTime::SECONDS_PER_DAY);

        $newTime = ($newSoD == $curSoD ? $this->time : LocalTime::ofSecondOfDay($newSoD));

        return $this->with($this->date->plusDays($totDays), $newTime);
    }

    /**
     * Compares this date-time to another date-time.
     *
     * @param LocalDateTime $that The date-time to compare to.
     *
     * @return integer The comparator value, negative if less, positive if greater, 0 if equal.
     */
    public function compareTo(LocalDateTime $that)
    {
        $cmp = $this->date->compareTo($that->date);

        if ($cmp == 0) {
            $cmp = $this->time->compareTo($that->time);
        }

        return $cmp;
    }

    /**
     * @param LocalDateTime $that
     *
     * @return boolean
     */
    public function isEqualTo(LocalDateTime $that)
    {
        return $this->compareTo($that) == 0;
    }

    /**
     * @param LocalDateTime $that
     *
     * @return boolean
     */
    public function isBefore(LocalDateTime $that)
    {
        return $this->compareTo($that) < 0;
    }

    /**
     * @param LocalDateTime $that
     *
     * @return boolean
     */
    public function isBeforeOrEqualTo(LocalDateTime $that)
    {
        return $this->compareTo($that) <= 0;
    }

    /**
     * @param LocalDateTime $that
     *
     * @return boolean
     */
    public function isAfter(LocalDateTime $that)
    {
        return $this->compareTo($that) > 0;
    }

    /**
     * @param LocalDateTime $that
     *
     * @return boolean
     */
    public function isAfterOrEqualTo(LocalDateTime $that)
    {
        return $this->compareTo($that) >= 0;
    }

    /**
     * @param TimeZoneOffset $offset
     *
     * @return integer
     */
    public function toEpochSecond(TimeZoneOffset $offset)
    {
        $epochDay = $this->getDate()->toEpochDay();
        $secs = $epochDay * 86400 + $this->getTime()->toSecondOfDay();
        $secs -= $offset->getTotalSeconds();

        return $secs;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->date->toString() . 'T' . $this->time->toString();
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
     *
     * @return string
     */
    public function format(Locale $locale)
    {
        $formatter = new \IntlDateFormatter((string) $locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
        $formatter->setTimeZone('UTC');

        $datetime = new \DateTime(null, new \DateTimeZone('UTC'));
        $this->getDate()->applyToDateTime($datetime);
        $this->getTime()->applyToDateTime($datetime);

        return $formatter->format($datetime);
    }
}
