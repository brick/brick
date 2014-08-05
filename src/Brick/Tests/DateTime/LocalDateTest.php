<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDate;
use Brick\DateTime\LocalTime;

/**
 * Unit tests for class LocalDate.
 */
class LocalDateTest extends AbstractTestCase
{
    /**
     * @var LocalDate
     */
    private $minDate;

    /**
     * @var LocalDate
     */
    private $maxDate;

    /**
     * @var integer
     */
    private $minValidEpochDay;

    /**
     * @var integer
     */
    private $maxValidEpochDay;

    public function setUp()
    {
        $this->minDate = LocalDate::minDate();
        $this->maxDate = LocalDate::maxDate();

        $this->minValidEpochDay = $this->minDate->toEpochDay();
        $this->maxValidEpochDay = $this->maxDate->toEpochDay();
    }

    public function testOf()
    {
        $this->assertLocalDateEquals(2007, 7, 15, LocalDate::of(2007, 7, 15));
    }

    /**
     * @dataProvider providerOfInvalidDateThrowsException
     * @expectedException \Brick\DateTime\DateTimeException
     *
     * @param integer $year  The year of the invalid date.
     * @param integer $month The month of the invalid date.
     * @param integer $day   The day of the invalid date.
     */
    public function testOfInvalidDateThrowsException($year, $month, $day)
    {
        LocalDate::of($year, $month, $day);
    }

    /**
     * @return array
     */
    public function providerOfInvalidDateThrowsException()
    {
        return [
            [2007, 2, 29],
            [2007, 4, 31],
            [2007, 1, 0],
            [2007, 1, 32],
            [2007, 0, 1],
            [2007, 13, 1],
            [~PHP_INT_MAX, 1, 1],
            [PHP_INT_MAX, 1, 1]
        ];
    }

    public function testOfYearDayNonLeap()
    {
        $date = LocalDate::of(2007, 1, 1);
        for ($i = 1; $i < 365; $i++) {
            $this->assertTrue(LocalDate::ofYearDay(2007, $i)->isEqualTo($date));
            $date = $date->plusDays(1);
        }
    }

    public function testOfYearDayLeap()
    {
        $date = LocalDate::of(2008, 1, 1);
        for ($i = 1; $i < 366; $i++) {
            $this->assertTrue(LocalDate::ofYearDay(2008, $i)->isEqualTo($date));
            $date = $date->plusDays(1);
        }
    }

    /**
     * @dataProvider providerOfInvalidYearDayThrowsException
     * @expectedException \Brick\DateTime\DateTimeException
     *
     * @param integer $year      The year.
     * @param integer $dayOfYear The day-of-year.
     */
    public function testOfInvalidYearDayThrowsException($year, $dayOfYear)
    {
        LocalDate::ofYearDay($year, $dayOfYear);
    }

    /**
     * @return array
     */
    public function providerOfInvalidYearDayThrowsException()
    {
        return [
            [2007, 366],
            [2007, 0],
            [2007, 367],
            [~ PHP_INT_MAX, 1],
            [PHP_INT_MAX, 1],
        ];
    }

    /**
     * @dataProvider providerOfEpochDay
     *
     * @param integer $epochDay     The epoch day.
     * @param string  $expectedDate The expected date string.
     */
    public function testOfEpochDay($epochDay, $expectedDate)
    {
        $this->assertSame($expectedDate, (string) LocalDate::ofEpochDay($epochDay));
    }

    /**
     * @return array
     */
    public function providerOfEpochDay()
    {
        return [
            [-100000, '1696-03-17'],
            [-10000, '1942-08-16'],
            [-1000, '1967-04-07'],
            [-100, '1969-09-23'],
            [-10, '1969-12-22'],
            [-1, '1969-12-31'],
            [0, '1970-01-01'],
            [1, '1970-01-02'],
            [10, '1970-01-11'],
            [100, '1970-04-11'],
            [1000, '1972-09-27'],
            [10000, '1997-05-19'],
            [100000, '2243-10-17']
        ];
    }

    /**
     * @dataProvider providerDayOfWeek
     *
     * @param integer $year      The year to test.
     * @param integer $month     The month to test.
     * @param integer $day       The day-of-month to test.
     * @param integer $dayOfWeek The expected day-of-week number.
     */
    public function testGetDayOfWeek($year, $month, $day, $dayOfWeek)
    {
        $this->assertSame($dayOfWeek, LocalDate::of($year, $month, $day)->getDayOfWeek()->getValue());
    }

    /**
     * @return array
     */
    public function providerDayOfWeek()
    {
        return [
            [2000, 1, 3, 1],
            [2000, 2, 8, 2],
            [2000, 3, 8, 3],
            [2000, 4, 6, 4],
            [2000, 5, 5, 5],
            [2000, 6, 3, 6],
            [2000, 7, 9, 7],
            [2000, 8, 7, 1],
            [2000, 9, 5, 2],
            [2000, 10, 11, 3],
            [2000, 11, 16, 4],
            [2000, 12, 29, 5],
            [2001, 1, 1, 1],
            [2001, 2, 6, 2],
            [2001, 3, 7, 3],
            [2001, 4, 5, 4],
            [2001, 5, 4, 5],
            [2001, 6, 9, 6],
            [2001, 7, 8, 7],
            [2001, 8, 6, 1],
            [2001, 9, 4, 2],
            [2001, 10, 10, 3],
            [2001, 11, 15, 4],
            [2001, 12, 21, 5]
        ];
    }

    /**
     * @dataProvider providerDayOfYear
     *
     * @param integer $year      The year to test.
     * @param integer $month     The month to test.
     * @param integer $day       The day-of-month to test.
     * @param integer $dayOfWeek The expected day-of-year number.
     */
    public function testGetDayOfYear($year, $month, $day, $dayOfWeek)
    {
        $this->assertSame($dayOfWeek, LocalDate::of($year, $month, $day)->getDayOfYear());
    }

    /**
     * @return array
     */
    public function providerDayOfYear()
    {
        return [
            [2000, 1, 1, 1],
            [2000, 1, 31, 31],
            [2000, 2, 1, 32],
            [2000, 2, 29, 60],
            [2000, 3, 1, 61],
            [2000, 3, 31, 91],
            [2000, 4, 1, 92],
            [2000, 4, 30, 121],
            [2000, 5, 1, 122],
            [2000, 5, 31, 152],
            [2000, 6, 1, 153],
            [2000, 6, 30, 182],
            [2000, 7, 1, 183],
            [2000, 7, 31, 213],
            [2000, 8, 1, 214],
            [2000, 8, 31, 244],
            [2000, 9, 1, 245],
            [2000, 9, 30, 274],
            [2000, 10, 1, 275],
            [2000, 10, 31, 305],
            [2000, 11, 1, 306],
            [2000, 11, 30, 335],
            [2000, 12, 1, 336],
            [2000, 12, 31, 366],
            [2001, 1, 1, 1],
            [2001, 1, 31, 31],
            [2001, 2, 1, 32],
            [2001, 2, 28, 59],
            [2001, 3, 1, 60],
            [2001, 3, 31, 90],
            [2001, 4, 1, 91],
            [2001, 4, 30, 120],
            [2001, 5, 1, 121],
            [2001, 5, 31, 151],
            [2001, 6, 1, 152],
            [2001, 6, 30, 181],
            [2001, 7, 1, 182],
            [2001, 7, 31, 212],
            [2001, 8, 1, 213],
            [2001, 8, 31, 243],
            [2001, 9, 1, 244],
            [2001, 9, 30, 273],
            [2001, 10, 1, 274],
            [2001, 10, 31, 304],
            [2001, 11, 1, 305],
            [2001, 11, 30, 334],
            [2001, 12, 1, 335],
            [2001, 12, 31, 365]
        ];
    }

    /**
     * @dataProvider providerPlusYears
     *
     * @param integer $y  The base year.
     * @param integer $m  The base month.
     * @param integer $d  The base day.
     * @param integer $ay The number of years to add.
     * @param integer $ey The expected resulting year.
     * @param integer $em The expected resulting month.
     * @param integer $ed The expected resulting day.
     */
    public function testPlusYears($y, $m, $d, $ay, $ey, $em, $ed)
    {
        $this->assertLocalDateEquals($ey, $em, $ed, LocalDate::of($y, $m, $d)->plusYears($ay));
    }

    /**
     * @return array
     */
    public function providerPlusYears()
    {
        return [
            [2014, 1, 2, 0, 2014, 1, 2],
            [2015, 2, 3, 1, 2016, 2, 3],
            [2016, 3, 4, -1, 2015, 3, 4]
        ];
    }

    /**
     * @dataProvider providerPlusMonths
     *
     * @param integer $y  The base year.
     * @param integer $m  The base month.
     * @param integer $d  The base day.
     * @param integer $am The number of months to add.
     * @param integer $ey The expected resulting year.
     * @param integer $em The expected resulting month.
     * @param integer $ed The expected resulting day.
     */
    public function testPlusMonths($y, $m, $d, $am, $ey, $em, $ed)
    {
        $this->assertLocalDateEquals($ey, $em, $ed, LocalDate::of($y, $m, $d)->plusMonths($am));
    }

    /**
     * @return array
     */
    public function providerPlusMonths()
    {
        return [
            [2014, 1, 2, 0, 2014, 1, 2],
            [2015, 2, 3, 1, 2015, 3, 3],
            [2015, 2, 3, 12, 2016, 2, 3],
            [2015, 2, 3, 13, 2016, 3, 3],
            [2016, 3, 4, -1, 2016, 2, 4],
            [2016, 3, 4, -3, 2015, 12, 4],
            [2016, 3, 4, -12, 2015, 3, 4],
            [2011, 12, 31, 1, 2012, 1, 31],
            [2011, 12, 31, 2, 2012, 2, 29],
            [2012, 12, 31, 1, 2013, 1, 31],
            [2012, 12, 31, 2, 2013, 2, 28],
            [2012, 12, 31, 3, 2013, 3, 31],
            [2013, 12, 31, 2, 2014, 2, 28],
            [2013, 12, 31, 4, 2014, 4, 30]
        ];
    }

    /**
     * @dataProvider providerPlusWeeks
     *
     * @param integer $y  The base year.
     * @param integer $m  The base month.
     * @param integer $d  The base day.
     * @param integer $aw The number of weeks to add.
     * @param integer $ey The expected resulting year.
     * @param integer $em The expected resulting month.
     * @param integer $ed The expected resulting day.
     */
    public function testPlusWeeks($y, $m, $d, $aw, $ey, $em, $ed)
    {
        $this->assertLocalDateEquals($ey, $em, $ed, LocalDate::of($y, $m, $d)->plusWeeks($aw));
    }

    /**
     * @return array
     */
    public function providerPlusWeeks()
    {
        return [
            [2014, 7, 31, 0, 2014, 7, 31],
            [2014, 7, 31, 1, 2014, 8, 7],
            [2014, 7, 31, 5, 2014, 9, 4],
            [2014, 7, 31, 30, 2015, 2, 26],
            [2014, 8, 2, 30, 2015, 2, 28],
            [2014, 8, 3, 30, 2015, 3, 1],
            [2014, 7, 31, -9, 2014, 5, 29]
        ];
    }

    /**
     * @dataProvider providerPlusDays
     *
     * @param integer $y  The base year.
     * @param integer $m  The base month.
     * @param integer $d  The base day.
     * @param integer $ad The number of days to add.
     * @param integer $ey The expected resulting year.
     * @param integer $em The expected resulting month.
     * @param integer $ed The expected resulting day.
     */
    public function testPlusDays($y, $m, $d, $ad, $ey, $em, $ed)
    {
        $this->assertLocalDateEquals($ey, $em, $ed, LocalDate::of($y, $m, $d)->plusDays($ad));
    }

    /**
     * @return array
     */
    public function providerPlusDays()
    {
        return [
            [2014, 1, 2, 0, 2014, 1, 2],
            [2014, 1, 2, 29, 2014, 1, 31],
            [2014, 1, 2, 30, 2014, 2, 1],
            [2014, 1, 2, 365, 2015, 1, 2],
            [2012, 1, 1, 365, 2012, 12, 31],
            [2012, 1, 1, 366, 2013, 1, 1],
            [2012, 1, 2, -1, 2012, 1, 1],
            [2012, 1, 1, -1, 2011, 12, 31]
        ];
    }

    public function testAtTime()
    {
        $localDateTime = LocalDate::of(1, 2, 3)->atTime(LocalTime::of(4, 5, 6, 7));
        $this->assertLocalDateTimeEquals(1, 2, 3, 4, 5, 6, 7, $localDateTime);
    }

    /**
     * @dataProvider providerToString
     *
     * @param integer $year     The year.
     * @param integer $month    The month.
     * @param integer $day      The day-of-month.
     * @param string  $expected The expected result string.
     */
    public function testToString($year, $month, $day, $expected)
    {
        $this->assertSame($expected, (string) LocalDate::of($year, $month, $day));
    }

    /**
     * @return array
     */
    public function providerToString()
    {
        return [
            [999, 1, 2, '0999-01-02'],
            [-2, 1, 1, '-0002-01-01']
        ];
    }
}
