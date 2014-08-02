<?php

namespace Brick\DateTime;

use Brick\DateTime\Utility\Math;
use Brick\Locale\Locale;
use Brick\Type\Cast;

/**
 * A time without a time-zone in the ISO-8601 calendar system, such as 10:15:30.
 *
 * This class is immutable.
 */
class LocalTime
{
    const HOURS_PER_DAY      = 24;
    const MINUTES_PER_HOUR   = 60;
    const MINUTES_PER_DAY    = 1440;
    const SECONDS_PER_MINUTE = 60;
    const SECONDS_PER_HOUR   = 3600;
    const SECONDS_PER_DAY    = 86400;
    const NANOS_PER_SECOND   = 1000000000;

    /**
     * The hour.
     *
     * @var integer
     */
    private $hour;

    /**
     * The minute.
     *
     * @var integer
     */
    private $minute;

    /**
     * The second.
     *
     * @var integer
     */
    private $second;

    /**
     * Private constructor. Use of() to obtain an instance.
     *
     * @param integer $hour   The hour to represent, validated as an integer in the range [0-23].
     * @param integer $minute The minute to represent, validated as an integer in the range [0-59].
     * @param integer $second The second to represent, validated as an integer in the range [0-59].
     */
    private function __construct($hour, $minute, $second)
    {
        $this->hour   = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    /**
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     *
     * @return LocalTime
     *
     * @throws \InvalidArgumentException
     * @throws DateTimeException
     */
    public static function of($hour, $minute, $second = 0)
    {
        $hour = Cast::toInteger($hour);
        $minute = Cast::toInteger($minute);
        $second = Cast::toInteger($second);

        if ($hour < 0 || $hour > 23) {
            throw new DateTimeException('Hour must be in the interval [0-23]');
        }

        if ($minute < 0 || $minute > 59) {
            throw new DateTimeException('Minute must be in the interval [0-59]');
        }

        if ($second < 0 || $second > 59) {
            throw new DateTimeException('Second must be in the interval [0-59]');
        }

        return new LocalTime($hour, $minute, $second);
    }

    /**
     * @param Parser\DateTimeParseResult $result
     *
     * @return LocalTime
     *
     * @throws DateTimeException If the time is not valid.
     */
    public static function from(Parser\DateTimeParseResult $result)
    {
        return LocalTime::of(
            $result->getField(Field\DateTimeField::HOUR_OF_DAY),
            $result->getField(Field\DateTimeField::MINUTE_OF_HOUR),
            $result->getOptionalField(Field\DateTimeField::SECOND_OF_MINUTE, 0)
        );
    }

    /**
     * Obtains an instance of `LocalTime` from a text string.
     *
     * @param string                     $text   The text to parse, such as `10:15`.
     * @param Parser\DateTimeParser|null $parser The parser to use. Defaults to the ISO 8601 parser.
     *
     * @return LocalTime
     *
     * @throws DateTimeException             If the time is not valid.
     * @throws Parser\DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, Parser\DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = Parser\DateTimeParsers::isoLocalTime();
        }

        return LocalTime::from($parser->parse($text));
    }

    /**
     * Creates a LocalTime instance from a number of seconds since midnight.
     *
     * @param integer $secondOfDay
     *
     * @return LocalTime
     * @throws DateTimeException
     */
    public static function ofSecondOfDay($secondOfDay)
    {
        $secondOfDay = (int) $secondOfDay;

        if ($secondOfDay < 0 || $secondOfDay >= self::SECONDS_PER_DAY) {
            throw new DateTimeException('Second of day must be in the interval [0-86399]');
        }

        $hours = Math::div($secondOfDay, self::SECONDS_PER_HOUR);
        $secondOfDay -= $hours * self::SECONDS_PER_HOUR;
        $minutes = Math::div($secondOfDay, self::SECONDS_PER_MINUTE);
        $secondOfDay -= $minutes * self::SECONDS_PER_MINUTE;

        return new LocalTime($hours, $minutes, $secondOfDay);
    }

    /**
     * Returns the current time, in the given time zone.
     *
     * @param TimeZone $timeZone
     *
     * @return LocalTime
     */
    public static function now(TimeZone $timeZone)
    {
        return ZonedDateTime::now($timeZone)->getTime();
    }

    /**
     * @return LocalTime
     */
    public static function midnight()
    {
        return new LocalTime(0, 0, 0);
    }

    /**
     * @return LocalTime
     */
    public static function noon()
    {
        return new LocalTime(12, 0, 0);
    }

    /**
     * Returns the smallest possible value for LocalTime.
     *
     * @return LocalTime
     */
    public static function min()
    {
        return LocalTime::midnight();
    }

    /**
     * Returns the highest possible value for LocalTime.
     *
     * @return LocalTime
     */
    public static function max()
    {
        return new LocalTime(23, 59, 59);
    }

    /**
     * @return integer
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @return integer
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @return integer
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * Returns a copy of this LocalTime with the hour-of-day value altered.
     *
     * @param integer $hour
     *
     * @return LocalTime
     */
    public function withHour($hour)
    {
        if ($hour == $this->hour) {
            return $this;
        }

        return new LocalTime($hour, $this->minute, $this->second);
    }

    /**
     * Returns a copy of this LocalTime with the minute-of-hour value altered.
     *
     * @param integer $minute
     *
     * @return LocalTime
     */
    public function withMinute($minute)
    {
        if ($minute == $this->minute) {
            return $this;
        }

        return new LocalTime($this->hour, $minute, $this->second);
    }

    /**
     * Returns a copy of this LocalTime with the second-of-minute value altered.
     *
     * @param integer $second
     *
     * @return LocalTime
     */
    public function withSecond($second)
    {
        if ($second == $this->second) {
            return $this;
        }

        return new LocalTime($this->hour, $this->minute, $second);
    }

    /**
     * Returns a copy of this LocalTime with the specified period in hours added.
     *
     * This adds the specified number of hours to this time, returning a new time.
     * The calculation wraps around midnight.
     *
     * This instance is immutable and unaffected by this method call.
     *
     * @param integer $hoursToAdd The hours to add, may be negative.
     *
     * @return LocalTime A LocalTime based on this time with the hours added.
     */
    public function plusHours($hoursToAdd)
    {
        if ($hoursToAdd == 0) {
            return $this;
        }

        $newHour = (($hoursToAdd % self::HOURS_PER_DAY) + $this->hour + self::HOURS_PER_DAY) % self::HOURS_PER_DAY;

        return new LocalTime($newHour, $this->minute, $this->second);
    }

    /**
     * Returns a copy of this LocalTime with the specified period in minutes added.
     *
     * This adds the specified number of minutes to this time, returning a new time.
     * The calculation wraps around midnight.
     *
     * This instance is immutable and unaffected by this method call.
     *
     * @param integer $minutesToAdd The minutes to add, may be negative.
     *
     * @return LocalTime A LocalTime based on this time with the minutes added.
     */
    public function plusMinutes($minutesToAdd)
    {
        if ($minutesToAdd == 0) {
            return $this;
        }

        $mofd = $this->hour * self::MINUTES_PER_HOUR + $this->minute;
        $newMofd = (($minutesToAdd % self::MINUTES_PER_DAY) + $mofd + self::MINUTES_PER_DAY) % self::MINUTES_PER_DAY;

        if ($mofd == $newMofd) {
            return $this;
        }

        $newHour = Math::div($newMofd, self::MINUTES_PER_HOUR);
        $newMinute = $newMofd % self::MINUTES_PER_HOUR;

        return new LocalTime($newHour, $newMinute, $this->second);
    }

    /**
     * Returns a copy of this LocalTime with the specified period in seconds added.
     *
     * @param integer $secondstoAdd The seconds to add, may be negative.
     *
     * @return LocalTime A LocalTime based on this time with the seconds added.
     */
    public function plusSeconds($secondstoAdd)
    {
        if ($secondstoAdd == 0) {
            return $this;
        }

        $sofd = $this->hour * self::SECONDS_PER_HOUR + $this->minute * self::SECONDS_PER_MINUTE + $this->second;
        $newSofd = (($secondstoAdd % self::SECONDS_PER_DAY) + $sofd + self::SECONDS_PER_DAY) % self::SECONDS_PER_DAY;

        if ($sofd == $newSofd) {
            return $this;
        }

        $newHour = Math::div($newSofd, self::SECONDS_PER_HOUR);
        $newMinute = Math::div($newSofd, self::SECONDS_PER_MINUTE) % self::MINUTES_PER_HOUR;
        $newSecond = $newSofd % self::SECONDS_PER_MINUTE;

        return new LocalTime($newHour, $newMinute, $newSecond);
    }

    /**
     * @param integer $hoursToSubtract
     *
     * @return LocalTime
     */
    public function minusHours($hoursToSubtract)
    {
        return $this->plusHours(- $hoursToSubtract);
    }

    /**
     * @param integer $minutesToSubtract
     *
     * @return LocalTime
     */
    public function minusMinutes($minutesToSubtract)
    {
        return $this->plusMinutes(- $minutesToSubtract);
    }

    /**
     * @param integer $secondsToSubtract
     *
     * @return LocalTime
     */
    public function minusSeconds($secondsToSubtract)
    {
        return $this->plusSeconds(- $secondsToSubtract);
    }

    /**
     * Returns the difference between this LocalTime and the given time, in seconds.
     *
     * The result is:
     *
     * * positive if this time is after the given time;
     * * negative if this time is before the given time;
     * * zero if the times are equal.
     *
     * @param LocalTime $that The time to compare to.
     *
     * @return integer The difference in seconds.
     */
    public function compareTo(LocalTime $that)
    {
        return $this->toSecondOfDay() - $that->toSecondOfDay();
    }

    /**
     * Checks if this LocalTime is equal to the specified time.
     *
     * @param LocalTime $that The time to compare to.
     *
     * @return boolean
     */
    public function isEqualTo(LocalTime $that)
    {
        return $this->compareTo($that) == 0;
    }

    /**
     * Checks if this LocalTime is greater than the specified time.
     *
     * @param LocalTime $that The time to compare to.
     *
     * @return boolean
     */
    public function isGreaterThan(LocalTime $that)
    {
        return $this->compareTo($that) > 0;
    }

    /**
     * Checks if this LocalTime is greater than, or equal to, the specified time.
     *
     * @param LocalTime $that The time to compare to.
     *
     * @return boolean
     */
    public function isGreaterThanOrEqualTo(LocalTime $that)
    {
        return $this->compareTo($that) >= 0;
    }

    /**
     * Checks if this LocalTime is less than the specified time.
     *
     * @param LocalTime $that The time to compare to.
     *
     * @return boolean
     */
    public function isLessThan(LocalTime $that)
    {
        return $this->compareTo($that) < 0;
    }

    /**
     * Checks if this LocalTime is less than, or equal to, the specified time.
     *
     * @param LocalTime $that The time to compare to.
     *
     * @return boolean
     */
    public function isLessThanOrEqualTo(LocalTime $that)
    {
        return $this->compareTo($that) <= 0;
    }

    /**
     * Returns a local date-time formed from this time at the specified date.
     *
     * @param LocalDate $date
     *
     * @return LocalDateTime
     */
    public function atDate(LocalDate $date)
    {
        return LocalDateTime::ofDateTime($date, $this);
    }

    /**
     * Returns the number of seconds since midnight.
     *
     * @return integer
     */
    public function toSecondOfDay()
    {
        return $this->hour * self::SECONDS_PER_HOUR
            + $this->minute * self::SECONDS_PER_MINUTE
            + $this->second;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return void
     *
     * @deprecated
     */
    public function applyToDateTime(\DateTime $dateTime)
    {
        $dateTime->setTime($this->hour, $this->minute, $this->second);
    }

    /**
     * Returns this time as a string, such as 10:15.
     *
     * The output will be one of the following ISO-8601 formats:
     *
     * * `HH:mm`
     * * `HH:mm:ss`
     *
     * The format used will be the shortest that outputs the full value of
     * the time where the omitted parts are implied to be zero.
     *
     * @return string A string representation of this time.
     */
    public function toString()
    {
        return ($this->second == 0)
            ? sprintf('%02u:%02u', $this->hour, $this->minute)
            : sprintf('%02u:%02u:%02u', $this->hour, $this->minute, $this->second);
    }

    /**
     * Returns this time as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @todo only supports HH:MM right now. Automatically switch between HH:MM & HH:MM:SS depending on SS==00?
     *
     * @param \Brick\Locale\Locale $locale
     *
     * @return string
     */
    public function format(Locale $locale)
    {
        $formatter = new \IntlDateFormatter((string) $locale, \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT);
        $formatter->setTimeZone('UTC');

        $datetime = new \DateTime(null, new \DateTimeZone('UTC'));
        $this->applyToDateTime($datetime);

        return $formatter->format($datetime);
    }

    /**
     * Returns the smallest LocalTime of an array.
     *
     * @param LocalTime[] $times An array of LocalTime objects.
     *
     * @return LocalTime The smallest LocalTime object.
     *
     * @throws DateTimeException If the array is empty.
     */
    public static function minOf(array $times)
    {
        if (count($times) == 0) {
            throw new DateTimeException('LocalTime::minOf() does not accept less than 1 parameter');
        }

        $min = LocalTime::max();

        foreach ($times as $time) {
            if ($time->isLessThan($min)) {
                $min = $time;
            }
        }

        return $min;
    }

    /**
     * Returns the highest LocalTime of an array.
     *
     * @param LocalTime[] $times An array of LocalTime objects.
     *
     * @return LocalTime The highest LocalTime object.
     *
     * @throws DateTimeException If the array is empty.
     */
    public static function maxOf(array $times)
    {
        if (count($times) == 0) {
            throw new DateTimeException('LocalTime::maxOf() does not accept less than 1 parameter');
        }

        $max = LocalTime::min();

        foreach ($times as $time) {
            if ($time->isGreaterThan($max)) {
                $max = $time;
            }
        }

        return $max;
    }
}
