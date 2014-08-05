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
     * @var string
     */
    private $id;

    /**
     * Private constructor. Use a factory method to obtain an instance.
     *
     * @param integer $totalSeconds The total offset in seconds, validated as an integer from -64800 to +64800.
     */
    private function __construct($totalSeconds)
    {
        $this->totalSeconds = $totalSeconds;

        if ($totalSeconds === 0) {
            $this->id = 'Z';
        } elseif ($totalSeconds < 0) {
            $this->id = '-' . LocalTime::ofSecondOfDay(-$totalSeconds);
        } else {
            $this->id = '+' . LocalTime::ofSecondOfDay($totalSeconds);
        }
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

        if (self::haveDifferentSigns($hours, $minutes, $seconds)) {
            throw new DateTimeException('Time zone offset hours, minutes and seconds must have the same sign');
        }
        if ($hours < -TimeZoneOffset::MAX_HOURS || $hours > TimeZoneOffset::MAX_HOURS) {
            throw new DateTimeException('Time zone offset hours must be in the range -18 to 18');
        }
        if ($minutes < -LocalTime::MINUTES_PER_HOUR || $minutes > LocalTime::MINUTES_PER_HOUR) {
            throw new DateTimeException('Time zone offset minutes must be in the range -59 to 59');
        }
        if ($seconds < -LocalTime::SECONDS_PER_MINUTE || $seconds > LocalTime::SECONDS_PER_MINUTE) {
            throw new DateTimeException('Time zone offset seconds must be in the range -59 to 59');
        }

        $totalSeconds = $hours * LocalTime::SECONDS_PER_HOUR + $minutes * LocalTime::SECONDS_PER_MINUTE + $seconds;

        if ($totalSeconds < -TimeZoneOffset::MAX_SECONDS || $totalSeconds > TimeZoneOffset::MAX_SECONDS) {
            throw new DateTimeException('Time zone offset not in valid range: -18:00 to +18:00');
        }

        return TimeZoneOffset::ofTotalSeconds($totalSeconds);
    }

    /**
     * @return TimeZoneOffset
     */
    public static function utc()
    {
        return new TimeZoneOffset(0);
    }

    /**
     * Checks whether the three integers have different signs.
     *
     * @param integer $a The first integer.
     * @param integer $b The second integer.
     * @param integer $c The third integer.
     *
     * @return boolean
     */
    private static function haveDifferentSigns($a, $b, $c)
    {
        return ($a > 0 && ($b < 0 || $c < 0))
            || ($a < 0 && ($b > 0 || $c > 0))
            || ($b > 0 && $c < 0)
            || ($b < 0 && $c > 0);
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
     * @param string  $text            The offset text representation.
     * @param integer $pos             The position to parse.
     * @param integer $length          The number of characters to parse.
     * @param boolean $precededByColon Whether this number should be preceded by a colon.
     *
     * @return integer The parsed number, from 0 to 99.
     *
     * @throws Parser\DateTimeParseException
     */
    private static function parseNumber($text, $pos, $length, $precededByColon)
    {
        if ($precededByColon && $text[$pos - 1] != ':') {
            throw Parser\DateTimeParseException::invalidTimeZoneOffset($text, 'Colon not found when expected');
        }

        $number = substr($text, $pos, $length);

        if (! ctype_digit($number)) {
            throw Parser\DateTimeParseException::invalidTimeZoneOffset($text, 'Non numeric characters found');
        }

        return (int) $number;
    }

    /**
     * Returns the total zone offset in seconds.
     *
     * This is the primary way to access the offset amount.
     * It returns the total of the hours, minutes and seconds fields as a
     * single offset that can be added to a time.
     *
     * @return integer The total zone offset amount in seconds.
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
        // We *need* to pass a third parameter (timezone), even though it will be ignored here.
        $tz = new \DateTimeZone('UTC');

        // DateTimeZone's constructor does not accept an offset,
        // but DateTime can provide such a DateTimeZone.
        return \DateTime::createFromFormat('O', $this->id, $tz)->getTimezone();
    }
}
