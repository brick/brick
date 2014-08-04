<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalDate;

/**
 * Unit tests for class LocalDate.
 */
class LocalDateTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @param integer   $year  The expected year.
     * @param integer   $month The expected month.
     * @param integer   $day   The expected day.
     * @param LocalDate $date  The date to test.
     */
    private function assertLocalDateEquals($year, $month, $day, LocalDate $date)
    {
        $this->assertSame($year, $date->getYear());
        $this->assertSame($month, $date->getMonth());
        $this->assertSame($day, $date->getDay());
    }

    public function setUp()
    {
        $this->minDate = LocalDate::minDate();
        $this->maxDate = LocalDate::maxDate();

        $this->minValidEpochDay = $this->minDate->toEpochDay();
        $this->maxValidEpochDay = $this->maxDate->toEpochDay();
    }

    public function testOf()
    {
        $date = LocalDate::of(2007, 7, 15);

        $this->assertEquals(2007, $date->getYear());
        $this->assertEquals(7, $date->getMonth());
        $this->assertEquals(15, $date->getDay());
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
}
