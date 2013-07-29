<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Period;

/**
 * Unit tests for class Period.
 */
class PeriodTest extends \PHPUnit_Framework_TestCase
{
    public function testZeroPeriod()
    {
        $period = Period::zero();

        $this->assertEquals(0, $period->getYears());
        $this->assertEquals(0, $period->getMonths());
        $this->assertEquals(0, $period->getDays());
        $this->assertEquals(0, $period->getHours());
        $this->assertEquals(0, $period->getMinutes());
        $this->assertEquals(0, $period->getSeconds());
    }

    public function testGetters()
    {
        $period = Period::of(6, 5, 4, 3, 2, 1);

        $this->assertEquals(6, $period->getYears());
        $this->assertEquals(5, $period->getMonths());
        $this->assertEquals(4, $period->getDays());
        $this->assertEquals(3, $period->getHours());
        $this->assertEquals(2, $period->getMinutes());
        $this->assertEquals(1, $period->getSeconds());
    }

    public function testWithYears()
    {
        $period = Period::of(1, 2, 3, 4, 5, 6);
        $expected = Period::of(9, 2, 3, 4, 5, 6);

        $this->assertTrue($period->withYears(9)->isEqualTo($expected));
    }

    public function testWithMonths()
    {
        $period = Period::of(1, 2, 3, 4, 5, 6);
        $expected = Period::of(1, 9, 3, 4, 5, 6);

        $this->assertTrue($period->withMonths(9)->isEqualTo($expected));
    }

    public function testWithDays()
    {
        $period = Period::of(1, 2, 3, 4, 5, 6);
        $expected = Period::of(1, 2, 9, 4, 5, 6);

        $this->assertTrue($period->withDays(9)->isEqualTo($expected));
    }

    public function testWithHours()
    {
        $period = Period::of(1, 2, 3, 4, 5, 6);
        $expected = Period::of(1, 2, 3, 9, 5, 6);

        $this->assertTrue($period->withHours(9)->isEqualTo($expected));
    }

    public function testWithMinutes()
    {
        $period = Period::of(1, 2, 3, 4, 5, 6);
        $expected = Period::of(1, 2, 3, 4, 9, 6);

        $this->assertTrue($period->withMinutes(9)->isEqualTo($expected));
    }

    public function testWithSeconds()
    {
        $period = Period::of(1, 2, 3, 4, 5, 6);
        $expected = Period::of(1, 2, 3, 4, 5, 9);

        $this->assertTrue($period->withSeconds(9)->isEqualTo($expected));
    }

    /**
     * @dataProvider isEqualToProvider
     *
     * @param int $hours   The hours of the period to compare.
     * @param int $minutes The minutes of the period to compare.
     * @param int $seconds The seconds of the period to compare.
     */
    public function testIsEqualTo($hours, $minutes, $seconds)
    {
        $oneHourPeriod = Period::ofTime(1, 0, 0);
        $period = Period::ofTime($hours, $minutes, $seconds);

        $this->assertTrue($period->isEqualTo($oneHourPeriod));
    }

    /**
     * @return array
     */
    public function isEqualToProvider()
    {
        return [
            [0, 60, 0],
            [0, 0, 3600],
            [0, 59, 60],
            [2, -60, 0],
            [3, -60, -3600],
            [1, -60, 3600],
            [1, 1, -60],
            [2, 0, -3600],
            [-1, 60, 3600],
            [-1, 30, 5400],
            [-1, -1, 7260],
            [-2, 182, -120]
        ];
    }

    /**
     * @dataProvider toDateIntervalProvider
     *
     * @param int $years
     * @param int $months
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     */
    public function testToDateInterval($years, $months, $days, $hours, $minutes, $seconds)
    {
        $period = Period::of($years, $months, $days, $hours, $minutes, $seconds);
        $dateInterval = $period->toDateInterval();

        $this->assertEquals($years, $dateInterval->y);
        $this->assertEquals($months, $dateInterval->m);
        $this->assertEquals($days, $dateInterval->d);
        $this->assertEquals($hours, $dateInterval->h);
        $this->assertEquals($minutes, $dateInterval->i);
        $this->assertEquals($seconds, $dateInterval->s);
    }

    /**
     * @return array
     */
    public function toDateIntervalProvider()
    {
        return [
            [1, -2, 3, -4, 5, -6],
            [-1, 2, -3, 4, -5, 6]
        ];
    }
}
