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
class Instant extends PointInTime
{
    /**
     * The number of seconds since the epoch of 1970-01-01T00:00:00Z.
     *
     * @var int
     */
    private $timestamp;

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
     * @param int $timestamp The timestamp, validated as an integer.
     */
    private function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param int $timestamp A UNIX timestamp.
     * @return Instant
     * @throws \InvalidArgumentException If the given timestamp is not a valid integer.
     */
    public static function of($timestamp)
    {
        return new Instant(Cast::toInteger($timestamp));
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
        return new Instant(Instant::getDefaultClock()->getTimestamp());
    }

    /**
     * Returns the minimum supported instant.
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
     * This could be used by an application as a "far future" instant.
     *
     * @return Instant
     */
    public static function max()
    {
        return new Instant(PHP_INT_MAX);
    }

    /**
     * Creates a new Instant, checking if a new instance is actually required.
     *
     * @param int $timestamp The timestamp, validated as an integer.
     * @return Instant
     */
    private function create($timestamp)
    {
        if ($timestamp == $this->timestamp) {
            return $this;
        }

        return new Instant($timestamp);
    }

    /**
     * @param Duration $duration
     * @return Instant
     */
    public function plus(Duration $duration)
    {
        return $this->create($this->timestamp + $duration->getSeconds());
    }

    /**
     * @param int $seconds
     * @return Instant
     */
    public function plusSeconds($seconds)
    {
        return $this->create($this->timestamp + Cast::toInteger($seconds));
    }

    /**
     * @param int $minutes
     * @return Instant
     */
    public function plusMinutes($minutes)
    {
        return $this->create($this->timestamp + LocalTime::SECONDS_PER_MINUTE * Cast::toInteger($minutes));
    }

    /**
     * @param int $hours
     * @return Instant
     */
    public function plusHours($hours)
    {
        return $this->create($this->timestamp + LocalTime::SECONDS_PER_HOUR * Cast::toInteger($hours));
    }

    /**
     * @param int $days
     * @return Instant
     */
    public function plusDays($days)
    {
        return $this->create($this->timestamp + LocalTime::SECONDS_PER_DAY * Cast::toInteger($days));
    }

    /**
     * @param Duration $duration
     * @return Instant
     */
    public function minus(Duration $duration)
    {
        return $this->create($this->timestamp - $duration->getSeconds());
    }

    /**
     * @param int $seconds
     * @return Instant
     */
    public function minusSeconds($seconds)
    {
        return $this->create($this->timestamp - Cast::toInteger($seconds));
    }

    /**
     * @param int $minutes
     * @return Instant
     */
    public function minusMinutes($minutes)
    {
        return $this->create($this->timestamp - LocalTime::SECONDS_PER_MINUTE * Cast::toInteger($minutes));
    }

    /**
     * @param int $hours
     * @return Instant
     */
    public function minusHours($hours)
    {
        return $this->create($this->timestamp - LocalTime::SECONDS_PER_HOUR * Cast::toInteger($hours));
    }

    /**
     * @param int $days
     * @return Instant
     */
    public function minusDays($days)
    {
        return $this->create($this->timestamp - LocalTime::SECONDS_PER_DAY * Cast::toInteger($days));
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Returns a ZonedDateTime representing this Instant, in the given TimeZone.
     *
     * @param TimeZone $timeZone
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
     * @return Instant
     */
    public static function fromDateTime(\DateTime $dateTime)
    {
        return new Instant($dateTime->getTimestamp());
    }

    /**
     * @param \DateTimeZone $dateTimeZone
     * @return \DateTime
     */
    public function toDateTime(\DateTimeZone $dateTimeZone)
    {
        $dateTime = new \DateTime('@' . $this->getTimestamp(), $dateTimeZone);
        $dateTime->setTimezone($dateTimeZone);

        return $dateTime;
    }
}
