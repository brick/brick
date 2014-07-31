<?php

namespace Brick\DateTime;

use Brick\Type\Cast;

/**
 * Represents a point in time, with a 1 second precision.
 *
 * Instant represents the computer view of the timeline. It unambiguously represents a point in time,
 * without any calendar concept of date, time or time zone.
 *
 * Instant is easily and unambiguously persistable as an integer. It is not very meaningful to humans,
 * but can be converted to a `ZonedDateTime` by providing a time zone.
 */
class Instant extends ReadableInstant
{
    /**
     * The number of seconds since the epoch of 1970-01-01T00:00:00Z.
     *
     * @var integer
     */
    private $timestamp;

    /**
     * The microseconds, in the range [0,999999].
     *
     * @var integer
     */
    private $microseconds;

    /**
     * The global default Clock to use.
     *
     * @todo Not a very clean approach. Having the app get the Clock from DI & use it explicitly could be better?
     *
     * @var Clock\Clock|null
     */
    private static $clock = null;

    /**
     * Sets the default clock.
     *
     * @param Clock\Clock $clock
     * @return void
     */
    public static function setDefaultClock(Clock\Clock $clock)
    {
        self::$clock = $clock;
    }

    /**
     * Returns the default clock. Defaults to the system clock unless overridden.
     *
     * @return Clock\Clock
     */
    public static function getDefaultClock()
    {
        if (self::$clock) {
            return self::$clock;
        } else {
            return new Clock\SystemClock();
        }
    }

    /**
     * Private constructor. Use of() to obtain an Instant.
     *
     * @param integer $timestamp    The timestamp, validated as an integer.
     * @param integer $microseconds The microseconds, validated as an integer in the range [0,999999].
     */
    private function __construct($timestamp, $microseconds = 0)
    {
        $this->timestamp    = $timestamp;
        $this->microseconds = $microseconds;
    }

    /**
     * @param integer $timestamp    The UNIX timestamp.
     * @param integer $microseconds The microseconds, in the range [0,999999].
     *
     * @return Instant
     *
     * @throws \InvalidArgumentException If any of the parameters is not valid.
     */
    public static function of($timestamp, $microseconds = 0)
    {
        $timestamp    = Cast::toInteger($timestamp);
        $microseconds = Cast::toInteger($microseconds);

        if ($microseconds < 0 || $microseconds > 999999) {
            throw new \InvalidArgumentException('Invalid number of microseconds.');
        }

        return new Instant($timestamp, $microseconds);
    }

    /**
     * @return Instant
     */
    public static function epoch()
    {
        return new Instant(0);
    }

    /**
     * @return Instant
     */
    public static function now()
    {
        return Instant::getDefaultClock()->getTime();
    }

    /**
     * Returns the minimum supported instant.
     *
     * This could be used by an application as a "far past" instant.
     *
     * @return Instant
     */
    public static function min()
    {
        return new Instant(~ PHP_INT_MAX);
    }

    /**
     * Returns the maximum supported instant.
     *
     * This could be used by an application as a "far future" instant.
     *
     * @return Instant
     */
    public static function max()
    {
        return new Instant(PHP_INT_MAX);
    }

    /**
     * @todo duration micros
     *
     * @param Duration $duration
     *
     * @return Instant
     */
    public function plus(Duration $duration)
    {
        if ($duration->isZero()) {
            return $this;
        }

        return new Instant($this->timestamp + $duration->getSeconds(), $this->microseconds);
    }

    /**
     * @param integer $seconds
     *
     * @return Instant
     */
    public function plusSeconds($seconds)
    {
        $seconds = Cast::toInteger($seconds);

        if ($seconds === 0) {
            return $this;
        }

        return new Instant($this->timestamp + $seconds, $this->microseconds);
    }

    /**
     * @param integer $minutes
     *
     * @return Instant
     */
    public function plusMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        if ($minutes === 0) {
            return $this;
        }

        $seconds = LocalTime::SECONDS_PER_MINUTE * $minutes;

        return new Instant($this->timestamp + $seconds, $this->microseconds);
    }

    /**
     * @param integer $hours
     *
     * @return Instant
     */
    public function plusHours($hours)
    {
        $hours = Cast::toInteger($hours);

        if ($hours === 0) {
            return $this;
        }

        $seconds = LocalTime::SECONDS_PER_HOUR * $hours;

        return new Instant($this->timestamp + $seconds, $this->microseconds);
    }

    /**
     * @param integer $days
     *
     * @return Instant
     */
    public function plusDays($days)
    {
        $days = Cast::toInteger($days);

        if ($days === 0) {
            return $this;
        }

        $seconds = LocalTime::SECONDS_PER_DAY * $days;

        return new Instant($this->timestamp + $seconds, $this->microseconds);
    }

    /**
     * @todo Duration micros
     *
     * @param Duration $duration
     *
     * @return Instant
     */
    public function minus(Duration $duration)
    {
        if ($duration->isZero()) {
            return $this;
        }

        return new Instant($this->timestamp - $duration->getSeconds(), $this->microseconds);
    }

    /**
     * @param integer $seconds
     *
     * @return Instant
     */
    public function minusSeconds($seconds)
    {
        $seconds = Cast::toInteger($seconds);

        if ($seconds === 0) {
            return $this;
        }

        return new Instant($this->timestamp - $seconds, $this->microseconds);
    }

    /**
     * @param integer $minutes
     *
     * @return Instant
     */
    public function minusMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        if ($minutes === 0) {
            return $this;
        }

        $seconds = LocalTime::SECONDS_PER_MINUTE * $minutes;

        return new Instant($this->timestamp - $seconds, $this->microseconds);
    }

    /**
     * @param integer $hours
     *
     * @return Instant
     */
    public function minusHours($hours)
    {
        $hours = Cast::toInteger($hours);

        if ($hours === 0) {
            return $this;
        }

        $seconds = LocalTime::SECONDS_PER_HOUR * $hours;

        return new Instant($this->timestamp - $seconds, $this->microseconds);
    }

    /**
     * @param integer $days
     *
     * @return Instant
     */
    public function minusDays($days)
    {
        $days = Cast::toInteger($days);

        if ($days === 0) {
            return $this;
        }

        $seconds = LocalTime::SECONDS_PER_DAY * $days;

        return new Instant($this->timestamp - $seconds, $this->microseconds);
    }

    /**
     * {@inheritdoc}
     */
    public function getInstant()
    {
        return $this;
    }

    /**
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return integer
     */
    public function getMicroseconds()
    {
        return $this->microseconds;
    }

    /**
     * Returns a ZonedDateTime representing this Instant, in the given TimeZone.
     *
     * @param TimeZone $timeZone
     *
     * @return ZonedDateTime
     */
    public function atTimeZone(TimeZone $timeZone)
    {
        return ZonedDateTime::ofInstant($this, $timeZone);
    }

    /**
     * @deprecated Use atTimeZone()
     *
     * @param TimeZone $timeZone
     *
     * @return ZonedDateTime
     */
    public function toZonedDateTime(TimeZone $timeZone)
    {
        return ZonedDateTime::ofInstant($this, $timeZone);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return ZonedDateTime::ofInstant($this, TimeZone::utc())->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return Instant
     */
    public static function fromDateTime(\DateTime $dateTime)
    {
        return new Instant($dateTime->getTimestamp());
    }

    /**
     * @param \DateTimeZone $dateTimeZone
     *
     * @return \DateTime
     */
    public function toDateTime(\DateTimeZone $dateTimeZone)
    {
        $dateTime = new \DateTime('@' . $this->timestamp, $dateTimeZone);
        $dateTime->setTimezone($dateTimeZone);

        return $dateTime;
    }
}
