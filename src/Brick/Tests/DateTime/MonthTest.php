<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Month;

/**
 * Unit tests for class Month.
 */
class MonthTest extends \PHPUnit_Framework_TestCase
{
    public function testConstants()
    {
        $this->assertEquals(1, Month::JANUARY);
        $this->assertEquals(2, Month::FEBRUARY);
        $this->assertEquals(3, Month::MARCH);
        $this->assertEquals(4, Month::APRIL);
        $this->assertEquals(5, Month::MAY);
        $this->assertEquals(6, Month::JUNE);
        $this->assertEquals(7, Month::JULY);
        $this->assertEquals(8, Month::AUGUST);
        $this->assertEquals(9, Month::SEPTEMBER);
        $this->assertEquals(10, Month::OCTOBER);
        $this->assertEquals(11, Month::NOVEMBER);
        $this->assertEquals(12, Month::DECEMBER);
    }

    /**
     * @dataProvider providerOfInvalidThrowsException
     * @expectedException \UnexpectedValueException
     *
     * @param integer $invalidMonth
     */
    public function testOfInvalidThrowsException($invalidMonth)
    {
        Month::of($invalidMonth);
    }

    /**
     * @return array
     */
    public function providerOfInvalidThrowsException()
    {
        return [
            [0],
            [13]
        ];
    }

    /**
     * @dataProvider providerMonthFactoryMethods
     *
     * @param Month   $month        The Month to test.
     * @param integer $integerValue The ISO 8601 day of the week integer value expected.
     */
    public function testMonthFactoryMethods(Month $month, $integerValue)
    {
        $this->assertEquals($month->getValue(), $integerValue);
    }

    /**
     * @return array
     */
    public function providerMonthFactoryMethods()
    {
        return [
            [Month::january(), Month::JANUARY],
            [Month::february(), Month::FEBRUARY],
            [Month::march(), Month::MARCH],
            [Month::april(), Month::APRIL],
            [Month::may(), Month::MAY],
            [Month::june(), Month::JUNE],
            [Month::july(), Month::JULY],
            [Month::august(), Month::AUGUST],
            [Month::september(), Month::SEPTEMBER],
            [Month::october(), Month::OCTOBER],
            [Month::november(), Month::NOVEMBER],
            [Month::december(), Month::DECEMBER],
        ];
    }

    public function testPlusMinusEntireYears()
    {
        foreach (Month::getMonths() as $month) {
            foreach ([-24, -12, 0, 12, 24] as $monthsToAdd) {
                $this->assertTrue($month->plus($monthsToAdd)->isEqualTo($month));
                $this->assertTrue($month->minus($monthsToAdd)->isEqualTo($month));
            }
        }
    }

    /**
     * @dataProvider providerPlusMonths
     *
     * @param integer $base     The base month number.
     * @param integer $amount   The amount of months to add.
     * @param integer $expected The expected month number.
     */
    public function testPlusMonths($base, $amount, $expected)
    {
        $base = Month::of($base);
        $expected = Month::of($expected);

        $this->assertTrue($base->plus($amount)->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerPlusMonths()
    {
        return [
            [1, -13, 12],
            [1, -12, 1],
            [1, -11, 2],
            [1, -10, 3],
            [1, -9, 4],
            [1, -8, 5],
            [1, -7, 6],
            [1, -6, 7],
            [1, -5, 8],
            [1, -4, 9],
            [1, -3, 10],
            [1, -2, 11],
            [1, -1, 12],
            [1, 0, 1],
            [1, 1, 2],
            [1, 2, 3],
            [1, 3, 4],
            [1, 4, 5],
            [1, 5, 6],
            [1, 6, 7],
            [1, 7, 8],
            [1, 8, 9],
            [1, 9, 10],
            [1, 10, 11],
            [1, 11, 12],
            [1, 12, 1],
            [1, 13, 2],

            [1, 1, 2],
            [2, 1, 3],
            [3, 1, 4],
            [4, 1, 5],
            [5, 1, 6],
            [6, 1, 7],
            [7, 1, 8],
            [8, 1, 9],
            [9, 1, 10],
            [10, 1, 11],
            [11, 1, 12],
            [12, 1, 1],

            [1, -1, 12],
            [2, -1, 1],
            [3, -1, 2],
            [4, -1, 3],
            [5, -1, 4],
            [6, -1, 5],
            [7, -1, 6],
            [8, -1, 7],
            [9, -1, 8],
            [10, -1, 9],
            [11, -1, 10],
            [12, -1, 11],
        ];
    }

    /**
     * @dataProvider providerMinusMonths
     *
     * @param integer $base     The base month number.
     * @param integer $amount   The amount of months to subtract.
     * @param integer $expected The expected month number.
     */
    public function testMinusMonths($base, $amount, $expected)
    {
        $base = Month::of($base);
        $expected = Month::of($expected);

        $this->assertTrue($base->minus($amount)->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerMinusMonths()
    {
        return [
            [1, -13, 2],
            [1, -12, 1],
            [1, -11, 12],
            [1, -10, 11],
            [1, -9, 10],
            [1, -8, 9],
            [1, -7, 8],
            [1, -6, 7],
            [1, -5, 6],
            [1, -4, 5],
            [1, -3, 4],
            [1, -2, 3],
            [1, -1, 2],
            [1, 0, 1],
            [1, 1, 12],
            [1, 2, 11],
            [1, 3, 10],
            [1, 4, 9],
            [1, 5, 8],
            [1, 6, 7],
            [1, 7, 6],
            [1, 8, 5],
            [1, 9, 4],
            [1, 10, 3],
            [1, 11, 2],
            [1, 12, 1],
            [1, 13, 12],
        ];
    }

    public function testFirstDayOfYearNotLeapYear()
    {
        $this->assertEquals(1, Month::january()->firstDayOfYear(false));
        $this->assertEquals(1 + 31, Month::february()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28, Month::march()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31, Month::april()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30, Month::may()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31, Month::june()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31 + 30, Month::july()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31 + 30 + 31, Month::august()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31 + 30 + 31 + 31, Month::september()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31 + 30 + 31 + 31 + 30, Month::october()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31, Month::november()->firstDayOfYear(false));
        $this->assertEquals(1 + 31 + 28 + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30, Month::december()->firstDayOfYear(false));
    }

    public function testFirstDayOfYearLeapYear()
    {
        $this->assertEquals(1, Month::january()->firstDayOfYear(true));
        $this->assertEquals(1 + 31, Month::february()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29, Month::march()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31, Month::april()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30, Month::may()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31, Month::june()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31 + 30, Month::july()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31 + 30 + 31, Month::august()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31 + 30 + 31 + 31, Month::september()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31 + 30 + 31 + 31 + 30, Month::october()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31, Month::november()->firstDayOfYear(true));
        $this->assertEquals(1 + 31 + 29 + 31 + 30 + 31 + 30 + 31 + 31 + 30 + 31 + 30, Month::december()->firstDayOfYear(true));
    }

    public function testToString()
    {
        $this->assertEquals('January', Month::january()->toString());
        $this->assertEquals('February', Month::february()->toString());
        $this->assertEquals('March', Month::march()->toString());
        $this->assertEquals('April', Month::april()->toString());
        $this->assertEquals('May', Month::may()->toString());
        $this->assertEquals('June', Month::june()->toString());
        $this->assertEquals('July', Month::july()->toString());
        $this->assertEquals('August', Month::august()->toString());
        $this->assertEquals('September', Month::september()->toString());
        $this->assertEquals('October', Month::october()->toString());
        $this->assertEquals('November', Month::november()->toString());
        $this->assertEquals('December', Month::december()->toString());
    }
}
