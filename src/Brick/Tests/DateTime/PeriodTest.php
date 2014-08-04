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

        $this->assertSame(0, $period->getYears());
        $this->assertSame(0, $period->getMonths());
        $this->assertSame(0, $period->getDays());
    }

    public function testGetters()
    {
        $period = Period::of(6, 5, 4, 3, 2, 1);

        $this->assertSame(6, $period->getYears());
        $this->assertSame(5, $period->getMonths());
        $this->assertSame(4, $period->getDays());
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

    /**
     * @dataProvider providerIsEqualTo
     *
     * @param integer $y1      The number of years in the 1st period.
     * @param integer $m1      The number of months in the 1st period.
     * @param integer $d1      The number of days in the 1st period.
     * @param integer $y2      The number of years in the 2nd period.
     * @param integer $m2      The number of months in the 2nd period.
     * @param integer $d2      The number of days in the 2nd period.
     * @param boolean $isEqual The expected return value.
     */
    public function testIsEqualTo($y1, $m1, $d1, $y2, $m2, $d2, $isEqual)
    {
        $p1 = Period::of($y1, $m1, $d1);
        $p2 = Period::of($y2, $m2, $d2);

        $this->assertSame($isEqual, $p1->isEqualTo($p2));
        $this->assertSame($isEqual, $p2->isEqualTo($p1));
    }

    /**
     * @return array
     */
    public function providerIsEqualTo()
    {
        return [
            [0, 0, 0, 0, 0, 0, true],
            [0, 0, 0, 0, 0, 1, false],
            [0, 0, 0, 0, 0, -1, false],
            [1, 1, 1, 1, 1, 1, true],
            [1, 1, 1, 1, 2, 1, false],
            [-1, -1, -1, -1, -1, -1, true],
            [-1, -1, -1, -1, -2, -1, false],
            [2, 2, 2, 2, 2, 2, true],
            [2, 2, 2, 3, 2, 2, false],
            [-2, -2, -2, -2, -2, -2, true],
            [-2, -2, -2, -3, -2, -2, false],
        ];
    }

    /**
     * @dataProvider providerToDateInterval
     *
     * @param integer $years
     * @param integer $months
     * @param integer $days
     */
    public function testToDateInterval($years, $months, $days)
    {
        $period = Period::of($years, $months, $days);
        $dateInterval = $period->toDateInterval();

        $this->assertEquals($years, $dateInterval->y);
        $this->assertEquals($months, $dateInterval->m);
        $this->assertEquals($days, $dateInterval->d);
    }

    /**
     * @return array
     */
    public function providerToDateInterval()
    {
        return [
            [1, -2, 3],
            [-1, 2, -3]
        ];
    }
}
