<?php

namespace Brick\DateTime;

/**
 * A time-zone. This is the parent class for `TimeZoneOffset` and `TimeZoneRegion`.
 *
 * * `TimeZoneOffset` represents a fixed offset from UTC such as `+02:00`.
 * * `TimeZoneRegion` represents a geographical region such as `Europe/London`.
 */
abstract class TimeZone
{
    /**
     * Obtains an instance of `TimeZone` from a string representation.
     *
     * @param string $text
     * @return TimeZone
     */
    public static function of($text)
    {
        if (strlen($text) <= 1 || $text[0] == '+' || $text[0] == '-') {
            return TimeZoneOffset::parse($text);
        }

        return TimeZoneRegion::of($text);
    }

    /**
     * @return TimeZone
     */
    public static function utc()
    {
        return Timezone::of('UTC');
    }

    /**
     * Returns the unique time-zone ID.
     *
     * @return string
     */
    abstract public function getId();

    /**
     * @param ReadableInstant $pointInTime The instant.
     * @return int The offset from UTC in seconds.
     */
    abstract public function getOffset(ReadableInstant $pointInTime);

    /**
     * @param TimeZone $other
     * @return bool
     */
    public function isEqualTo(TimeZone $other)
    {
        return $this->getId() == $other->getId();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }

    /**
     * @param \DateTimeZone $dateTimeZone
     * @return TimeZone
     */
    public static function fromDateTimeZone(\DateTimeZone $dateTimeZone)
    {
        return TimeZone::of($dateTimeZone->getName());
    }

    /**
     * Returns an equivalent native `DateTimeZone` object for this TimeZone.
     *
     * @return \DateTimeZone The native DateTimeZone object.
     */
    abstract public function toDateTimeZone();
}
