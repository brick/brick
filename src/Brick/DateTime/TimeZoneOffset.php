<?php

namespace Brick\DateTime;

use Brick\DateTime\Field\DateTimeField;
use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\Parser\DateTimeParser;
use Brick\DateTime\Parser\TimeZoneOffsetParser;
use Brick\Type\Cast;

/**
 * A time-zone offset from Greenwich/UTC, such as `+02:00`.
 */
class TimeZoneOffset extends TimeZone
{
    /**
     * The absolute maximum seconds.
     */
    const MAX_SECONDS = 64800;

    /**
     * The absolute maximum hours.
     */
    const MAX_HOURS = 18;

    /**
     * @var integer
     */
    private $totalSeconds;

    /**
     * The string representation of this time-zone offset.
     *
     * This is generated on-the-fly, and will be null before the first call to getId().
     *
     * @var string|null
     */
    private $id = null;

    /**
     * Private constructor. Use a factory method to obtain an instance.
     *
     * @param integer $totalSeconds The total offset in seconds, validated as an integer from -64800 to +64800.
     */
    private function __construct($totalSeconds)
    {
        $this->totalSeconds = $totalSeconds;
    }

    /**
     * @param Parser\DateTimeParseResult $result
     *
     * @return TimeZoneOffset
     */
    public static function from(Parser\DateTimeParseResult $result)
    {
        $hour = $result->getField(DateTimeField::TIME_ZONE_OFFSET_HOUR);
        $minute = $result->getField(DateTimeField::TIME_ZONE_OFFSET_MINUTE);
        $second = $result->getOptionalField(DateTimeField::TIME_ZONE_OFFSET_SECOND, 0);

        if ($result->getField(DateTimeField::TIME_ZONE_OFFSET_SIGN) === '-') {
            $hour = -$hour;
            $minute = -$minute;
            $second = -$second;
        }

        return self::of($hour, $minute, $second);
    }

    /**
     * Obtains an instance of `TimeZoneOffset` using an offset in hours, minutes and seconds.
     *
     * @param integer $hours   The time-zone offset in hours, from -18 to +18.
     * @param integer $minutes The time-zone offset in minutes, from 0 to ±59, sign matching hours.
     * @param integer $seconds The time-zone offset in seconds, from 0 to ±59, sign matching hours and minute.
     *
     * @return TimeZoneOffset
     *
     * @throws DateTimeException
     */
    public static function of($hours, $minutes = 0, $seconds = 0)
    {
        $hours = Cast::toInteger($hours);
        $minutes = Cast::toInteger($minutes);
        $seconds = Cast::toInteger($seconds);

        if ($hours < -TimeZoneOffset::MAX_HOURS || $hours > TimeZoneOffset::MAX_HOURS) {
            throw new DateTimeException('Time zone offset hours must be in the range -18 to 18');
        }
        if ($minutes < -LocalTime::MINUTES_PER_HOUR || $minutes > LocalTime::MINUTES_PER_HOUR) {
            throw new DateTimeException('Time zone offset minutes must be in the range -59 to 59');
        }
        if ($seconds < -LocalTime::SECONDS_PER_MINUTE || $seconds > LocalTime::SECONDS_PER_MINUTE) {
            throw new DateTimeException('Time zone offset seconds must be in the range -59 to 59');
        }

        $err = ($hours > 0 && ($minutes < 0 || $seconds < 0))
            || ($hours < 0 && ($minutes > 0 || $seconds > 0))
            || ($minutes > 0 && $seconds < 0)
            || ($minutes < 0 && $seconds > 0);

        if ($err) {
            throw new DateTimeException('Time zone offset hours, minutes and seconds must have the same sign');
        }

        $totalSeconds = $hours * LocalTime::SECONDS_PER_HOUR + $minutes * LocalTime::SECONDS_PER_MINUTE + $seconds;

        if ($totalSeconds < -TimeZoneOffset::MAX_SECONDS || $totalSeconds > TimeZoneOffset::MAX_SECONDS) {
            throw new DateTimeException('Time zone offset not in valid range: -18:00 to +18:00');
        }

        return new TimeZoneOffset($totalSeconds);
    }

    /**
     * @return TimeZoneOffset
     */
    public static function utc()
    {
        return new TimeZoneOffset(0);
    }

    /**
     * Obtains an instance of `TimeZoneOffset` specifying the total offset in seconds.
     *
     * The offset must be in the range `-18:00` to `+18:00`, which corresponds to -64800 to +64800.
     *
     * @param integer $totalSeconds The total offset in seconds.
     *
     * @return TimeZoneOffset
     *
     * @throws DateTimeException
     */
    public static function ofTotalSeconds($totalSeconds)
    {
        $totalSeconds = Cast::toInteger($totalSeconds);

        if ($totalSeconds < -TimeZoneOffset::MAX_SECONDS || $totalSeconds > TimeZoneOffset::MAX_SECONDS) {
            throw new DateTimeException('Time zone offset not in valid range: -18:00 to +18:00');
        }

        return new TimeZoneOffset($totalSeconds);
    }

    /**
     * Parses a time-zone offset.
     *
     * The following ISO 8601 formats are accepted:
     * 
     * * `Z` - for UTC
     * * `±hh:mm`
     * * `±hh:mm:ss`
     *
     * Note that ± means either the plus or minus symbol.
     *
     * @param string              $text
     * @param DateTimeParser|null $parser
     *
     * @return TimeZoneOffset
     *
     * @throws DateTimeParseException
     */
    public static function parse($text, DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = new TimeZoneOffsetParser();
        }

        return TimeZoneOffset::from($parser->parse($text));
    }

    /**
     * Returns the total time-zone offset in seconds.
     *
     * This is the primary way to access the offset amount.
     * It returns the total of the hours, minutes and seconds fields as a
     * single offset that can be added to a time.
     *
     * @return integer The total time-zone offset amount in seconds.
     */
    public function getTotalSeconds()
    {
        return $this->totalSeconds;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        if ($this->id === null) {
            if ($this->totalSeconds < 0) {
                $this->id = '-' . LocalTime::ofSecondOfDay(- $this->totalSeconds);
            } elseif ($this->totalSeconds > 0) {
                $this->id = '+' . LocalTime::ofSecondOfDay($this->totalSeconds);
            } else {
                $this->id = 'Z';
            }
        }

        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(ReadableInstant $pointInTime)
    {
        return $this->totalSeconds;
    }

    /**
     * {@inheritdoc}
     */
    public function toDateTimeZone()
    {
        return new \DateTimeZone($this->getId());
    }
}
