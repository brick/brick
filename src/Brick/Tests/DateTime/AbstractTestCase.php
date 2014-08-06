<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Duration;
use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalDateTime;
use Brick\DateTime\LocalTime;
use Brick\DateTime\MonthDay;
use Brick\DateTime\Period;
use Brick\DateTime\ReadableInstant;
use Brick\DateTime\YearMonth;

/**
 * Base class for DateTime tests.
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param integer         $epochSecond The expected epoch second.
     * @param integer         $nano        The expected nanosecond adjustment.
     * @param ReadableInstant $instant     The instant to test.
     */
    protected function assertReadableInstantEquals($epochSecond, $nano, ReadableInstant $instant)
    {
        $this->assertSame($epochSecond, $instant->getEpochSecond());
        $this->assertSame($nano, $instant->getNano());
    }

    /**
     * @param integer   $year  The expected year.
     * @param integer   $month The expected month.
     * @param integer   $day   The expected day.
     * @param LocalDate $date  The local date to test.
     */
    protected function assertLocalDateEquals($year, $month, $day, LocalDate $date)
    {
        $this->assertSame($year, $date->getYear());
        $this->assertSame($month, $date->getMonth());
        $this->assertSame($day, $date->getDay());
    }

    /**
     * @param integer   $hour   The expected hour.
     * @param integer   $minute The expected minute.
     * @param integer   $second The expected second.
     * @param integer   $nano   The expected nano-of-second.
     * @param LocalTime $time   The local time to test.
     */
    protected function assertLocalTimeEquals($hour, $minute, $second, $nano, LocalTime $time)
    {
        $this->assertSame($hour, $time->getHour());
        $this->assertSame($minute, $time->getMinute());
        $this->assertSame($second, $time->getSecond());
        $this->assertSame($nano, $time->getNano());
    }

    /**
     * @param integer       $y        The expected year.
     * @param integer       $m        The expected month.
     * @param integer       $d        The expected day.
     * @param integer       $h        The expected hour.
     * @param integer       $i        The expected minute.
     * @param integer       $s        The expected second.
     * @param integer       $n        The expected nano-of-second.
     * @param LocalDateTime $dateTime The local date-time to test.
     */
    protected function assertLocalDateTimeEquals($y, $m, $d, $h, $i, $s, $n, LocalDateTime $dateTime)
    {
        $this->assertSame($y, $dateTime->getYear());
        $this->assertSame($m, $dateTime->getMonth());
        $this->assertSame($d, $dateTime->getDay());
        $this->assertSame($h, $dateTime->getHour());
        $this->assertSame($i, $dateTime->getMinute());
        $this->assertSame($s, $dateTime->getSecond());
        $this->assertSame($n, $dateTime->getNano());
    }

    /**
     * @param integer   $year      The expected year.
     * @param integer   $month     The expected month.
     * @param YearMonth $yearMonth The year-month to test.
     */
    protected function assertYearMonthEquals($year, $month, YearMonth $yearMonth)
    {
        $this->assertSame($year, $yearMonth->getYear());
        $this->assertSame($month, $yearMonth->getMonth());
    }

    /**
     * @param integer  $month    The expected month.
     * @param integer  $day      The expected day.
     * @param MonthDay $monthDay The month-day to test.
     */
    protected function assertMonthDayEquals($month, $day, MonthDay $monthDay)
    {
        $this->assertSame($month, $monthDay->getMonth());
        $this->assertSame($day, $monthDay->getDay());
    }

    /**
     * @param integer  $seconds  The expected seconds.
     * @param integer  $nanos    The expected nanos.
     * @param Duration $duration The duration to test.
     */
    protected function assertDurationEquals($seconds, $nanos, Duration $duration)
    {
        $this->assertSame($seconds, $duration->getSeconds());
        $this->assertSame($nanos, $duration->getNanos());
    }

    /**
     * @param integer $years  The expected number of years in the period.
     * @param integer $months The expected number of months in the period.
     * @param integer $days   The expected number of days in the period.
     * @param Period  $period The period to test.
     */
    protected function assertPeriodEquals($years, $months, $days, Period $period)
    {
        $this->assertSame($years, $period->getYears());
        $this->assertSame($months, $period->getMonths());
        $this->assertSame($days, $period->getDays());
    }
}
