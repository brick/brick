<?php

namespace Brick\DateTime;

use Brick\DateTime\Field\DateTimeField;
use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\Parser\DateTimeParser;
use Brick\DateTime\Parser\TimeZoneRegionParser;

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
     * @param \DateTimeZone $zone
     */
    private function __construct(\DateTimeZone $zone)
    {
        $this->zone = $zone;
    }

    /**
     * @param string $id The region id.
     *
     * @return TimeZoneRegion
     *
     * @throws DateTimeException If the region id is invalid.
     */
    public static function of($id)
    {
        $id = (string) $id;

        if ($id === '' || $id === 'Z' || $id === 'z' || $id[0] === '+' || $id[0] === '-') {
            // DateTimeZone would accept offsets, but TimeZoneRegion targets regions only.
            throw DateTimeException::unknownTimeZoneRegion($id);
        }

        try {
            return new TimeZoneRegion(new \DateTimeZone($id));
        } catch (\Exception $e) {
            throw DateTimeException::unknownTimeZoneRegion($id);
        }
    }

    /**
     * Parses a region id, such as 'Europe/London'.
     *
     * @param string               $text
     * @param DateTimeParser|null $parser
     *
     * @return TimeZoneRegion
     *
     * @throws DateTimeParseException
     */
    public static function parse($text, DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = new TimeZoneRegionParser();
        }

        return TimeZoneRegion::from($parser->parse($text));
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

        return self::of($result->getField(DateTimeField::TIME_ZONE_REGION));
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
