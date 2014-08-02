<?php

namespace Brick\DateTime;

use Brick\DateTime\Utility\Math;
use Brick\DateTime\Utility\Time;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Brick\Type\Cast;

/**
 * Represents a duration of time measured in seconds.
 *
 * This class is immutable.
 *
 * @todo format() method to output something like 3 hours, 4 minutes and 30 seconds
 */
class Duration
{
    /**
     * The duration in seconds.
     *
     * @var integer
     */
    private $seconds;

    /**
     * The microseconds adjustment to the duration, in the range 0 to 999,999.
     *
     * A duration of -1 microsecond is stored as -1 seconds plus 999,999 microseconds.
     *
     * @var integer
     */
    private $microseconds;

    /**
     * Private constructor. Use one of the factory methods to obtain a Duration.
     *
     * @param integer $seconds      The duration in seconds, validated as an integer.
     * @param integer $microseconds The microseconds adjustment, validated as an integer in the range 0 to 999,999.
     */
    private function __construct($seconds, $microseconds = 0)
    {
        $this->seconds      = $seconds;
        $this->microseconds = $microseconds;

        assert(is_int($seconds), 'seconds is integer (' . gettype($seconds) . ')');
        assert(is_int($microseconds), 'microseconds is integer (' . gettype($microseconds) . ')');
        assert($microseconds >= 0 && $microseconds <= 999999, 'microseconds is in range (' . $microseconds . ')');
    }

    /**
     * Returns a zero length Duration.
     *
     * @return \Brick\DateTime\Duration
     */
    public static function zero()
    {
        return new Duration(0);
    }

    /**
     * Obtains an instance of `Duration` by parsing a text string.
     *
     * This will parse the string produced by `toString()` which is
     * the ISO-8601 format `PTnS` where `n` is the number of seconds.
     *
     * If the duration contains a number of seconds with a decimal point,
     * and the number of decimal places exceeds the microsecond precision (6 digits),
     * the duration will be silently truncated to 6 decimal places.
     *
     * @param string $text
     *
     * @return \Brick\DateTime\Duration
     *
     * @throws \Brick\DateTime\Parser\DateTimeParseException
     */
    public static function parse($text)
    {
        if (preg_match('/^PT(\-?)([0-9]+)(?:\.([0-9]+))?S$/i', $text, $matches) === 0) {
            throw Parser\DateTimeParseException::invalidDuration($text);
        }

        try {
            $seconds = Cast::toInteger($matches[1] . $matches[2]);
        } catch (\InvalidArgumentException $e) {
            throw Parser\DateTimeParseException::invalidDuration($text);
        }

        if (isset($matches[3])) {
            $microseconds = substr($matches[3] . '000000', 0, 6);
            $microseconds = (int) $microseconds;
        } else {
            $microseconds = 0;
        }

        if ($matches[1] === '-' && $microseconds !== 0) {
            $microseconds = 1000000 - $microseconds;
            $seconds--;
        }

        return new Duration($seconds, $microseconds);
    }

    /**
     * Returns a Duration representing a number of seconds and an adjustment in microseconds.
     *
     * This method allows an arbitrary number of microseconds to be passed in.
     * The factory will alter the values of the second and microsecond in order
     * to ensure that the stored microsecond is in the range 0 to 999,999.
     * For example, the following will result in the exactly the same duration:
     *
     * * Duration.ofSeconds(3, 1);
     * * Duration.ofSeconds(4, -999999);
     * * Duration.ofSeconds(2, 1000001);
     *
     * @param integer $seconds
     * @param integer $microAdjustment
     *
     * @return \Brick\DateTime\Duration
     */
    public static function ofSeconds($seconds, $microAdjustment = 0)
    {
        $seconds = Cast::toInteger($seconds);
        $microAdjustment = Cast::toInteger($microAdjustment);

        $microseconds = $microAdjustment % 1000000;
        $seconds += ($microAdjustment - $microseconds) / 1000000;

        if ($microseconds < 0) {
            $microseconds += 1000000;
            $seconds--;
        }

        return new Duration($seconds, $microseconds);
    }

    /**
     * Returns a Duration from a number of minutes.
     *
     * @param integer $minutes
     *
     * @return \Brick\DateTime\Duration
     */
    public static function ofMinutes($minutes)
    {
        return new Duration(60 * Cast::toInteger($minutes));
    }

    /**
     * Returns a Duration from a number of hours.
     *
     * @param integer $hours
     *
     * @return \Brick\DateTime\Duration
     */
    public static function ofHours($hours)
    {
        return new Duration(3600 * Cast::toInteger($hours));
    }

    /**
     * Returns a Duration from a number of days.
     *
     * @param integer $days
     *
     * @return \Brick\DateTime\Duration
     */
    public static function ofDays($days)
    {
        return new Duration(86400 * Cast::toInteger($days));
    }

    /**
     * Returns a Duration representing the time elapsed between two instants.
     *
     * A Duration represents a directed distance between two points on the time-line.
     * As such, this method will return a negative duration if the end is before the start.
     *
     * @param \Brick\DateTime\ReadableInstant $startInclusive The start instant, inclusive.
     * @param \Brick\DateTime\ReadableInstant $endExclusive   The end instant, exclusive.
     *
     * @return \Brick\DateTime\Duration
     */
    public static function between(ReadableInstant $startInclusive, ReadableInstant $endExclusive)
    {
        $startInclusive = $startInclusive->getInstant();
        $endExclusive = $endExclusive->getInstant();

        $seconds = $endExclusive->getTimestamp() - $startInclusive->getTimestamp();
        $microseconds = $endExclusive->getMicroseconds() - $startInclusive->getMicroseconds();

        return Duration::ofSeconds($seconds, $microseconds);
    }

    /**
     * Returns a Duration representing the time elapsed since the given instant.
     *
     * @param \Brick\DateTime\ReadableInstant $startInclusive
     *
     * @return \Brick\DateTime\Duration
     */
    public static function since(ReadableInstant $startInclusive)
    {
        return Duration::between($startInclusive, Instant::now());
    }

    /**
     * Returns whether this Duration is zero length.
     *
     * @return boolean
     */
    public function isZero()
    {
        return $this->seconds === 0 && $this->microseconds === 0;
    }

    /**
     * Returns whether this Duration is positive, excluding zero.
     *
     * @return boolean
     */
    public function isPositive()
    {
        return $this->seconds > 0 || ($this->seconds === 0 && $this->microseconds > 0);
    }

    /**
     * Returns whether this Duration is positive or zero.
     *
     * @return boolean
     */
    public function isPositiveOrZero()
    {
        return $this->seconds >= 0;
    }

    /**
     * Returns whether this Duration is negative, excluding zero.
     *
     * @return boolean
     */
    public function isNegative()
    {
        return $this->seconds < 0;
    }

    /**
     * Returns whether this Duration is negative or zero.
     *
     * @return boolean
     */
    public function isNegativeOrZero()
    {
        return $this->seconds < 0 || ($this->seconds === 0 && $this->microseconds === 0);
    }

    /**
     * Returns a copy of this Duration with the specified duration added.
     *
     * @param \Brick\DateTime\Duration $duration
     *
     * @return \Brick\DateTime\Duration
     */
    public function plus(Duration $duration)
    {
        if ($duration->isZero()) {
            return $this;
        }

        Time::add(
            $this->seconds,
            $this->microseconds,
            $duration->seconds,
            $duration->microseconds,
            $seconds,
            $microseconds
        );

        return new Duration($seconds, $microseconds);
    }

    /**
     * Returns a copy of this Duration with the specified duration in seconds added.
     *
     * @param integer $seconds
     *
     * @return \Brick\DateTime\Duration
     */
    public function plusSeconds($seconds)
    {
        $seconds = Cast::toInteger($seconds);

        if ($seconds === 0) {
            return $this;
        }

        return new Duration($this->seconds + $seconds, $this->microseconds);
    }

    /**
     * Returns a copy of this Duration with the specified duration in minutes added.
     *
     * @param integer $minutes
     *
     * @return \Brick\DateTime\Duration
     */
    public function plusMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        return $this->plusSeconds($minutes * LocalTime::SECONDS_PER_MINUTE);
    }

    /**
     * Returns a copy of this Duration with the specified duration in hours added.
     *
     * @param integer $hours
     *
     * @return \Brick\DateTime\Duration
     */
    public function plusHours($hours)
    {
        $hours = Cast::toInteger($hours);

        return $this->plusSeconds($hours * LocalTime::SECONDS_PER_HOUR);
    }

    /**
     * Returns a copy of this Duration with the specified duration in days added.
     *
     * @param integer $days
     *
     * @return \Brick\DateTime\Duration
     */
    public function plusDays($days)
    {
        $days = Cast::toInteger($days);

        return $this->plusSeconds($days * LocalTime::SECONDS_PER_DAY);
    }

    /**
     * Returns a copy of this Duration with the specified duration added.
     *
     * @param \Brick\DateTime\Duration $duration
     *
     * @return \Brick\DateTime\Duration
     */
    public function minus(Duration $duration)
    {
        if ($duration->isZero()) {
            return $this;
        }

        return $this->plus($duration->negated());
    }

    /**
     * Returns a copy of this Duration with the specified duration in seconds subtracted.
     *
     * @param integer $seconds
     *
     * @return \Brick\DateTime\Duration
     */
    public function minusSeconds($seconds)
    {
        $seconds = Cast::toInteger($seconds);

        return $this->plusSeconds(-$seconds);
    }

    /**
     * Returns a copy of this Duration with the specified duration in minutes subtracted.
     *
     * @param integer $minutes
     *
     * @return \Brick\DateTime\Duration
     */
    public function minusMinutes($minutes)
    {
        $minutes = Cast::toInteger($minutes);

        return $this->plusMinutes(-$minutes);
    }

    /**
     * Returns a copy of this Duration with the specified duration in hours subtracted.
     *
     * @param integer $hours
     *
     * @return \Brick\DateTime\Duration
     */
    public function minusHours($hours)
    {
        $hours = Cast::toInteger($hours);

        return $this->plusHours(-$hours);
    }

    /**
     * Returns a copy of this Duration with the specified duration in days subtracted.
     *
     * @param integer $days
     *
     * @return \Brick\DateTime\Duration
     */
    public function minusDays($days)
    {
        $days = Cast::toInteger($days);

        return $this->plusDays(-$days);
    }

    /**
     * Returns a copy of this Duration multiplied by the given value.
     *
     * @param integer $multiplicand
     *
     * @return \Brick\DateTime\Duration
     */
    public function multipliedBy($multiplicand)
    {
        $multiplicand = Cast::toInteger($multiplicand);

        if ($multiplicand === 1) {
            return $this;
        }

        $seconds = $this->seconds * $multiplicand;
        $totalmicros = $this->microseconds * $multiplicand;

        $microseconds = $totalmicros % 1000000;
        $seconds += ($totalmicros - $microseconds) / 1000000;

        return new Duration($seconds, $microseconds);
    }

    /**
     * Creates a Duration out of a BigDecimal representing a number of seconds.
     *
     * @param BigDecimal $decimal
     *
     * @return Duration
     */
    private function create(BigDecimal $decimal)
    {
        $micros = $decimal->withPointMovedRight(6)->toBigInteger();
        $divRem = $micros->divideAndRemainder(1000000);

        return Duration::ofSeconds($divRem[0]->toInteger(), $divRem[1]->toInteger());
    }

    /**
     * Returns a copy of this Duration divided by the given value.
     *
     * If this yields an inexact result, the result will be rounded down.
     *
     * @param integer $divisor
     *
     * @return \Brick\DateTime\Duration
     */
    public function dividedBy($divisor)
    {
        $divisor = Cast::toInteger($divisor);

        if ($divisor === 0) {
            throw new \InvalidArgumentException('Cannot divide a Duration by zero.');
        }

        if ($divisor === 1) {
            return $this;
        }

        $decimal = $this->toSeconds()->dividedBy($divisor, null, RoundingMode::DOWN);

        return $this->create($decimal);
    }

    /**
     * Returns a copy of this Duration with the length negated.
     *
     * @return \Brick\DateTime\Duration
     */
    public function negated()
    {
        if ($this->isZero()) {
            return $this;
        }

        $seconds = -$this->seconds;
        $microseconds = $this->microseconds;

        if ($microseconds !== 0) {
            $microseconds = 1000000 - $microseconds;
            $seconds--;
        }

        return new Duration($seconds, $microseconds);
    }

    /**
     * Returns a copy of this Duration with a positive length.
     *
     * @return \Brick\DateTime\Duration
     */
    public function abs()
    {
        return $this->isNegative() ? $this->negated() : $this;
    }

    /**
     * Compares this Duration to the specified duration.
     *
     * @param \Brick\DateTime\Duration $that The other duration to compare to.
     *
     * @return integer [-1,0,1] If this number is less than, equal to, or greater than the given number.
     */
    public function compareTo(Duration $that)
    {
        $seconds = $this->seconds - $that->seconds;

        if ($seconds !== 0) {
            return $seconds > 0 ? 1 : -1;
        }

        $microseconds = $this->microseconds - $that->microseconds;

        if ($microseconds === 0) {
            return 0;
        }

        return $microseconds > 0 ? 1 : -1;
    }

    /**
     * Checks if this Duration is equal to the specified duration.
     *
     * @param \Brick\DateTime\Duration $that
     *
     * @return boolean
     */
    public function isEqualTo(Duration $that)
    {
        return $this->compareTo($that) === 0;
    }

    /**
     * Checks if this Duration is greater than the specified duration.
     *
     * @param \Brick\DateTime\Duration $that
     *
     * @return boolean
     */
    public function isGreaterThan(Duration $that)
    {
        return $this->compareTo($that) > 0;
    }

    /**
     * Checks if this Duration is greater, or equal to than the specified duration.
     *
     * @param \Brick\DateTime\Duration $that
     *
     * @return boolean
     */
    public function isGreaterThanOrEqualTo(Duration $that)
    {
        return $this->compareTo($that) >= 0;
    }

    /**
     * Checks if this Duration is less than the specified duration.
     *
     * @param \Brick\DateTime\Duration $that
     *
     * @return boolean
     */
    public function isLessThan(Duration $that)
    {
        return $this->compareTo($that) < 0;
    }

    /**
     * Checks if this Duration is less than, or equal to the specified duration.
     *
     * @param \Brick\DateTime\Duration $that
     *
     * @return boolean
     */
    public function isLessThanOrEqualTo(Duration $that)
    {
        return $this->compareTo($that) <= 0;
    }

    /**
     * Returns the total length in seconds of this Duration.
     *
     * @return integer
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * Returns the microseconds adjustment of this Duration.
     *
     * @return integer
     */
    public function getMicroseconds()
    {
        return $this->microseconds;
    }

    /**
     * Returns the duration as a BigDecimal representing the number of seconds and microseconds.
     *
     * @return BigDecimal
     */
    public function toSeconds()
    {
        return BigDecimal::of($this->seconds)->plus(BigDecimal::ofUnscaledValue($this->microseconds, 6));
    }

    /**
     * Returns an ISO-8601 seconds based string representation of this duration.
     *
     * If the microseconds part is zero, it is omitted from the output.
     * Examples: `PT123S`, `PT123.456789S`.
     *
     * @return string
     */
    public function toString()
    {
        $seconds = $this->seconds;
        $microseconds = $this->microseconds;

        $string = 'PT';

        if ($seconds < 0) {
            $string .= '-';
            $seconds = -$seconds;

            if ($microseconds !== 0) {
                $microseconds = 1000000 - $microseconds;
                $seconds--;
            }
        }

        $string .= $seconds;

        if ($microseconds !== 0) {
            $string .= sprintf('.%06d', $microseconds);
        }

        return $string . 'S';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
