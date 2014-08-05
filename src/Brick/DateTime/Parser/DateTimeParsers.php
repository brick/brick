<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\Field\DateTimeField;

/**
 * Provides common implementations of `DateTimeParser`.
 */
final class DateTimeParsers
{
    /**
     * Private constructor. This utility class cannot be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * @param $string
     *
     * @return StringLiteralParser
     */
    public static function literal($string)
    {
        return new StringLiteralParser($string);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoYear()
    {
        return new NumberParser(DateTimeField::YEAR, 4, 9, true);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoMonthOfYear()
    {
        return new NumberParser(DateTimeField::MONTH_OF_YEAR, 2, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoDayOfMonth()
    {
        return new NumberParser(DateTimeField::DAY_OF_MONTH, 2, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoHourOfDay()
    {
        return new NumberParser(DateTimeField::HOUR_OF_DAY, 2, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoMinuteOfHour()
    {
        return new NumberParser(DateTimeField::MINUTE_OF_HOUR, 2, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoSecondOfMinute()
    {
        return new NumberParser(DateTimeField::SECOND_OF_MINUTE, 2, 2);
    }

    /**
     * @return DateTimeParser
     */
    public static function isoNanoOfSecond()
    {
        return new NumberParser(DateTimeField::NANO_OF_SECOND, 1, 9, false, true);
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
            ->append(self::literal('-'))
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
            ->append(self::literal('-'))
            ->append(self::isoMonthOfYear())
            ->append(self::literal('-'))
            ->append(self::isoDayOfMonth())
            ->toParser();
    }

    /**
     * Returns a parser for an ISO local time such as `10:15`, `10:15:30` or `10:15:30.123456`.
     *
     * @return DateTimeParser
     */
    public static function isoLocalTime()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoHourOfDay())
            ->append(self::literal(':'))
            ->append(self::isoMinuteOfHour())
            ->optionalStart()
            ->append(self::literal(':'))
            ->append(self::isoSecondOfMinute())
            ->optionalStart()
            ->append(self::literal('.'))
            ->append(self::isoNanoOfSecond())
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
            ->append(self::literal('T'))
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
            ->append(self::isoLocalDateTime())
            ->append(new TimeZoneOffsetParser())
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
            ->optionalStart()
            ->append(self::literal('['))
            ->append(new TimeZoneRegionParser())
            ->append(self::literal(']'))
            ->toParser();
    }
}
