<?php

namespace Brick\DateTime;

use Brick\DateTime\Utility\Time;
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
     * Returns an Instant representing a number of seconds and an adjustment in microseconds.
     *
     * This method allows an arbitrary number of microseconds to be passed in.
     * The factory will alter the values of the second and microsecond in order
     * to ensure that the stored microsecond is in the range 0 to 999,999.
     * For example, the following will result in the exactly the same duration:
     *
     * * Instant::of(3, 1);
     * * Duration::of(4, -999999);
     * * Duration::of(2, 1000001);
     *
     * @param integer $timestamp       The number of seconds since the UNIX epoch of 1970-01-01T00:00:00Z.
     * @param integer $microAdjustment The adjustment to the epoch second in microseconds.
     *
     * @return Instant
     *
     * @throws \InvalidArgumentException If any of the parameters is not valid.
     */
    public static function of($timestamp, $microAdjustment = 0)
    {
        $seconds = Cast::toInteger($timestamp);
        $microAdjustment = Cast::toInteger($microAdjustment);

        $microseconds = $microAdjustment % 1000000;
        $seconds += ($microAdjustment - $microseconds) / 1000000;

        if ($microseconds < 0) {
            $microseconds += 1000000;
            $seconds--;
        }

        return new Instant($seconds, $microseconds);
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
     * @param Duration $duration
     *
     * @return Instant
     */
    public function plus(Duration $duration)
    {
        if ($duration->isZero()) {
            return $this;
        }

        Time::add(
            $this->timestamp,
            $this->microseconds,
            $duration->getSeconds(),
            $duration->getMicroseconds(),
            $seconds,
            $microseconds
        );

        return new Instant($seconds, $microseconds);
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

        Time::add($this->timestamp, $this->microseconds, $seconds, 0, $seconds, $microseconds);

        return new Instant($seconds, $microseconds);
    }

    /**
     * @param integer $minutes
     *
     * @return Instant
     */
    public function plusMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        return $this->plusSeconds($minutes * LocalTime::SECONDS_PER_MINUTE);
    }

    /**
     * @param integer $hours
     *
     * @return Instant
     */
    public function plusHours($hours)
    {
        $hours = Cast::toInteger($hours);

        return $this->plusSeconds($hours * LocalTime::SECONDS_PER_HOUR);
    }

    /**
     * @param integer $days
     *
     * @return Instant
     */
    public function plusDays($days)
    {
        $days = Cast::toInteger($days);

        return $this->plusSeconds($days * LocalTime::SECONDS_PER_DAY);
    }

    /**
     * @param Duration $duration
     *
     * @return Instant
     */
    public function minus(Duration $duration)
    {
        if ($duration->isZero()) {
            return $this;
        }

        return $this->plus($duration->negated());
    }

    /**
     * @param integer $seconds
     *
     * @return Instant
     */
    public function minusSeconds($seconds)
    {
        $seconds = Cast::toInteger($seconds);

        return $this->plusSeconds(-$seconds);
    }

    /**
     * @param integer $minutes
     *
     * @return Instant
     */
    public function minusMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        return $this->plusMinutes(-$minutes);
    }

    /**
     * @param integer $hours
     *
     * @return Instant
     */
    public function minusHours($hours)
    {
        $hours = Cast::toInteger($hours);

        return $this->plusHours(-$hours);
    }

    /**
     * @param integer $days
     *
     * @return Instant
     */
    public function minusDays($days)
    {
        $days = Cast::toInteger($days);

        return $this->plusDays(-$days);
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
