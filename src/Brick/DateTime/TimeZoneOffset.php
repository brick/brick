<?php

namespace Brick\DateTime;

use Brick\Type\Cast;

/**
 * A time-zone offset from Greenwich/UTC, such as `+02:00`.
 */
class TimeZoneOffset extends TimeZone
{
    /**
     * The abs maximum seconds.
     */
    const MAX_SECONDS = 64800;

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
        $this->id = $this->buildId();
    }

    /**
     * Returns the string representation of this time-zone offset.
     *
     * @return string
     */
    private function buildId()
    {
        if ($this->totalSeconds == 0) {
            return 'Z';
        }

        // The format follows the conventions of LocalTime.
        $result = LocalTime::ofSecondOfDay(abs($this->totalSeconds))->toString();

        return ($this->totalSeconds >= 0 ? '+' : '-') . $result;
    }

    /**
     * Obtains an instance of `TimeZoneOffset` using an offset in hours.
     *
     * @param integer $hours The time-zone offset in hours, from -18 to +18.
     *
     * @return TimeZoneOffset
     */
    public static function ofHours($hours)
    {
        return TimeZoneOffset::ofHoursMinutesSeconds($hours, 0, 0);
    }

    /**
     * Obtains an instance of `TimeZoneOffset` using an offset in hours and minutes.
     *
     * @param integer $hours   The time-zone offset in hours, from -18 to +18.
     * @param integer $minutes The time-zone offset in minutes, from 0 to ±59, sign matching hours.
     *
     * @return TimeZoneOffset
     */
    public static function ofHoursMinutes($hours, $minutes)
    {
        return TimeZoneOffset::ofHoursMinutesSeconds($hours, $minutes, 0);
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
    public static function ofHoursMinutesSeconds($hours, $minutes, $seconds)
    {
        $hours = Cast::toInteger($hours);
        $minutes = Cast::toInteger($minutes);
        $seconds = Cast::toInteger($seconds);

        if (self::haveDifferentSigns($hours, $minutes, $seconds)) {
            throw new DateTimeException('Time zone offset hours, minutes and seconds must have the same sign');
        }
        if (abs($hours) > 18) {
            throw new DateTimeException('Time zone offset hours must be in the range -18 to 18');
        }
        if (abs($minutes) > 59) {
            throw new DateTimeException('Time zone offset minutes must be in the range -59 to 59');
        }
        if (abs($seconds) > 59) {
            throw new DateTimeException('Time zone offset seconds must be in the range -59 to 59');
        }

        $totalSeconds = $hours * 3600 + $minutes * 60 + $seconds;

        return TimeZoneOffset::ofTotalSeconds($totalSeconds);
    }

    /**
     * @return TimeZoneOffset
     */
    public static function utc()
    {
        return TimeZoneOffset::ofTotalSeconds(0);
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

        if (abs($totalSeconds) > TimeZoneOffset::MAX_SECONDS) {
            throw new DateTimeException('Time zone offset not in valid range: -18:00 to +18:00');
        }

        return new TimeZoneOffset($totalSeconds);
    }

    /**
     * Obtains an instance of `TimeZoneOffset` from a text string.
     *
     * The following formats are accepted:
     * 
     * * `Z` - for UTC
     * * `±h`
     * * `±hh`
     * * `±hh:mm`
     * * `±hhmm`
     * * `±hh:mm:ss`
     * * `±hhmmss`
     *
     * Note that ± means either the plus or minus symbol.
     *
     * @param string $text
     *
     * @return TimeZoneOffset
     *
     * @throws Parser\DateTimeParseException
     */
    public static function parse($text)
    {
        if ($text == 'Z') {
            return self::utc();
        }

        switch (strlen($text)) {
            case 2:
                $hours = self::parseNumber($text, 1, 1, false);
                $minutes = 0;
                $seconds = 0;
                break;
            case 3:
                $hours = self::parseNumber($text, 1, 2, false);
                $minutes = 0;
                $seconds = 0;
                break;
            case 5:
                $hours = self::parseNumber($text, 1, 2, false);
                $minutes = self::parseNumber($text, 3, 2, false);
                $seconds = 0;
                break;
            case 6:
                $hours = self::parseNumber($text, 1, 2, false);
                $minutes = self::parseNumber($text, 4, 2, true);
                $seconds = 0;
                break;
            case 7;
                $hours = self::parseNumber($text, 1, 2, false);
                $minutes = self::parseNumber($text, 3, 2, false);
                $seconds = self::parseNumber($text, 5, 2, false);
                break;
            case 9:
                $hours = self::parseNumber($text, 1, 2, false);
                $minutes = self::parseNumber($text, 4, 2, true);
                $seconds = self::parseNumber($text, 7, 2, true);
                break;
            default:
                throw Parser\DateTimeParseException::invalidTimeZoneOffset($text);
        }

        switch ($text[0]) {
            case '+':
                return self::ofHoursMinutesSeconds($hours, $minutes, $seconds);
            case '-':
                return self::ofHoursMinutesSeconds(- $hours, - $minutes, - $seconds);
            default:
                throw Parser\DateTimeParseException::invalidTimeZoneOffset($text, 'Plus/minus not found when expected');
        }
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
     * Gets the total zone offset in seconds.
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
