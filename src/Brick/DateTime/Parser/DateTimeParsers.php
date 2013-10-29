<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\Field\DateTimeField;

/**
 * Provides common implementations of `DateTimeParser`.
 */
final class DateTimeParsers
{
    /**
     * Private constructor since this is a utility class.
     */
    private function __construct()
    {
    }

    /**
     * @return DateTimeParser
     */
    public static function isoYear()
    {
        return new NumberParser(DateTimeField::YEAR, 4, 10, NumberParser::SIGN_EXCEEDS_PAD);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoMonthOfYear()
    {
        return new NumberParser(DateTimeField::MONTH_OF_YEAR, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoDayOfMonth()
    {
        return new NumberParser(DateTimeField::DAY_OF_MONTH, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoHourOfDay()
    {
        return new NumberParser(DateTimeField::HOUR_OF_DAY, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoMinuteOfHour()
    {
        return new NumberParser(DateTimeField::MINUTE_OF_HOUR, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoSecondOfMinute()
    {
        return new NumberParser(DateTimeField::SECOND_OF_MINUTE, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoTimeZoneOffset()
    {
        return new TimeZoneOffsetParser();
    }

    /**
     * @return DateTimeParser
     */
    public static function isoTimeZoneRegion()
    {
        return new TimeZoneRegionParser();
    }

    /**
     * Returns a parser for an ISO year-month such as `2011-12`.
     *
     * @return DateTimeParser
     */
    public static function isoYearMonth()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoYear())
            ->appendLiteral('-')
            ->append(self::isoMonthOfYear())
            ->toParser();
    }

    /**
     * Returns a parser for an ISO local date such as `2011-12-03`.
     *
     * @return DateTimeParser
     */
    public static function isoLocalDate()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoYear())
            ->appendLiteral('-')
            ->append(self::isoMonthOfYear())
            ->appendLiteral('-')
            ->append(self::isoDayOfMonth())
            ->toParser();
    }

    /**
     * Returns a parser for an ISO local time such as `10:15` or `10:15:30`.
     *
     * @return DateTimeParser
     */
    public static function isoLocalTime()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoHourOfDay())
            ->appendLiteral(':')
            ->append(self::isoMinuteOfHour())
            ->startOptional()
            ->appendLiteral(':')
            ->append(self::isoSecondOfMinute())
            ->toParser();
    }

    /**
     * Returns a parser for an ISO local date-time such as `2011-12-03T10:15:30`.
     *
     * @return DateTimeParser
     */
    public static function isoLocalDateTime()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoLocalDate())
            ->appendLiteral('T')
            ->append(self::isoLocalTime())
            ->toParser();
    }

    /**
     * Returns a parser for an ISO date-time with an offset, such as `2011-12-03T10:15:30+01:00`.
     *
     * @return DateTimeParser
     */
    public static function isoOffsetDateTime()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoLocalDate())
            ->append(self::isoTimeZoneOffset())
            ->toParser();
    }

    /**
     * Returns a parser for an ISO date-time with offset & region, such as `2011-12-03T10:15:30+01:00[Europe/Paris]`.
     *
     * @return DateTimeParser
     */
    public static function isoZonedDateTime()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoOffsetDateTime())
            ->startOptional()
            ->appendLiteral('[')
            ->append(self::isoTimeZoneRegion())
            ->appendLiteral(']')
            ->toParser();
    }
}
