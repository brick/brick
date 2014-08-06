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
     * @param string $string
     *
     * @return StringLiteralParser
     */
    public static function literal($string)
    {
        return new StringLiteralParser($string);
    }

    /**
     * @param string $field
     *
     * @return DateTimeParser
     */
    public static function isoYear($field)
    {
        return new NumberParser($field, 4, 9, true);
    }

    /**
     * @param string $field
     *
     * @return DateTimeParser
     */
    public static function isoMonthOfYear($field)
    {
        return new NumberParser($field, 2, 2);
    }

    /**
     * @param string $field
     *
     * @return DateTimeParser
     */
    public static function isoDayOfMonth($field)
    {
        return new NumberParser($field, 2, 2);
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
            ->append(self::isoYear(DateTimeField::YEAR))
            ->append(self::literal('-'))
            ->append(self::isoMonthOfYear(DateTimeField::MONTH_OF_YEAR))
            ->toParser();
    }

    /**
     * Returns a parser for an ISO month-day such as `--12-31`.
     *
     * @return DateTimeParser
     */
    public static function isoMonthDay()
    {
        return DateTimeParserBuilder::create()
            ->append(self::literal('--'))
            ->append(self::isoMonthOfYear(DateTimeField::MONTH_OF_YEAR))
            ->append(self::literal('-'))
            ->append(self::isoDayOfMonth(DateTimeField::DAY_OF_MONTH))
            ->toParser();
    }

    /**
     * Returns a parser for an ISO local date such as `2011-12-03`.
     *
     * @param boolean $intervalEnd
     *
     * @return DateTimeParser
     */
    public static function isoLocalDate($intervalEnd = false)
    {
        $yearField  = $intervalEnd ? DateTimeField::END_YEAR : DateTimeField::YEAR;
        $monthField = $intervalEnd ? DateTimeField::END_MONTH_OF_YEAR : DateTimeField::MONTH_OF_YEAR;
        $dayField   = $intervalEnd ? DateTimeField::END_DAY_OF_MONTH : DateTimeField::DAY_OF_MONTH;

        return DateTimeParserBuilder::create()
            ->append(self::isoYear($yearField))
            ->append(self::literal('-'))
            ->append(self::isoMonthOfYear($monthField))
            ->append(self::literal('-'))
            ->append(self::isoDayOfMonth($dayField))
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
     * Returns a parser for an ISO local date range, such as `2011-12-03/2011-12-04`.
     *
     * @return DateTimeParser
     */
    public static function isoLocalDateRange()
    {
        return DateTimeParserBuilder::create()
            ->append(self::isoLocalDate(false))
            ->append(self::literal('/'))
            ->append(self::isoLocalDate(true))
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
