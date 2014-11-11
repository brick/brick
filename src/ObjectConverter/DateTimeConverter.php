<?php

namespace Brick\ObjectConverter;

use Brick\ObjectConverter\Exception\ObjectNotConvertibleException;

use Brick\DateTime\Duration;
use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalDateTime;
use Brick\DateTime\LocalTime;
use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\TimeZoneOffset;
use Brick\DateTime\TimeZoneRegion;
use Brick\DateTime\YearMonth;
use Brick\DateTime\ZonedDateTime;

/**
 * Handles conversion between date-time objects and strings.
 */
class DateTimeConverter implements ObjectConverter
{
    /**
     * {@inheritdoc}
     */
    public function shrink($object)
    {
        if ($object instanceof Duration) {
            return (string) $object;
        }

        if ($object instanceof LocalDate) {
            return (string) $object;
        }

        if ($object instanceof LocalTime) {
            return (string) $object;
        }

        if ($object instanceof LocalDateTime) {
            return (string) $object;
        }

        if ($object instanceof TimeZoneOffset) {
            return (string) $object;
        }

        if ($object instanceof TimeZoneRegion) {
            return (string) $object;
        }

        if ($object instanceof YearMonth) {
            return (string) $object;
        }

        if ($object instanceof ZonedDateTime) {
            return (string) $object;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function expand($className, $value, array $options = [])
    {
        try {
            switch ($className) {
                case Duration::class:
                    return Duration::parse($value);

                case LocalDate::class:
                    return LocalDate::parse($value);

                case LocalTime::class:
                    return LocalTime::parse($value);

                case LocalDateTime::class:
                    return LocalDateTime::parse($value);

                case TimeZoneOffset::class:
                    return TimeZoneOffset::parse($value);

                case TimeZoneRegion::class:
                    return TimeZoneRegion::parse($value);

                case YearMonth::class:
                    return YearMonth::parse($value);

                case ZonedDateTime::class:
                    return ZonedDateTime::parse($value);
            }
        } catch (DateTimeParseException $e) {
            throw new ObjectNotConvertibleException($e->getMessage(), $e->getCode(), $e);
        }

        return null;
    }
}
