<?php

namespace Brick\DateTime;

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
            throw new DateTimeException(sprintf('Unknown or bad timezone (%s)', $timezone));
        }
    }

    /**
     * @param string $text
     *
     * @return TimeZoneRegion
     */
    public static function of($text)
    {
        return new TimeZoneRegion($text);
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
