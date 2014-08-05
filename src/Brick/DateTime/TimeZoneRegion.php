<?php

namespace Brick\DateTime;

use Brick\DateTime\Field\DateTimeField;

/**
 * A geographical region where the same time-zone rules apply, such as `Europe/London`.
 */
class TimeZoneRegion extends TimeZone
{
    /**
     * @var \DateTimeZone
     */
    private $zone;

    /**
     * Private constructor. Use a factory method to obtain an instance.
     *
     * @param string $timezone
     *
     * @throws DateTimeException
     */
    private function __construct($timezone)
    {
        try {
            $this->zone = new \DateTimeZone($timezone);
        } catch (\Exception $e) {
            throw new DateTimeException(sprintf('Unknown or bad timezone "%s".', $timezone));
        }
    }

    /**
     * Parses a region id, such as 'Europe/London'.
     *
     * @param string $text
     *
     * @return TimeZoneRegion
     *
     * @throws \Brick\DateTime\Parser\DateTimeParseException
     */
    public static function parse($text)
    {
        return new TimeZoneRegion($text);
    }

    /**
     * @param Parser\DateTimeParseResult $result
     *
     * @return TimeZoneRegion|null
     */
    public static function from(Parser\DateTimeParseResult $result)
    {
        if (! $result->hasField(DateTimeField::TIME_ZONE_REGION)) {
            return null;
        }

        return self::parse($result->getField(DateTimeField::TIME_ZONE_REGION));
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->zone->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(ReadableInstant $pointInTime)
    {
        $instant = $pointInTime->getInstant();
        $dateTime = $instant->toDateTime(new \DateTimeZone('UTC'));

        return $this->zone->getOffset($dateTime);
    }

    /**
     * {@inheritdoc}
     */
    public function toDateTimeZone()
    {
        return clone $this->zone;
    }
}
