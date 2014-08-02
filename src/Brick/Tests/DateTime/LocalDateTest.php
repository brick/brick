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
}
