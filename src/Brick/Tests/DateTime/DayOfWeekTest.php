<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\DayOfWeek;
use Brick\DateTime\LocalDate;

/**
 * Unit tests for class DayOfWeek.
 */
class DayOfWeekTest extends AbstractTestCase
{
    /**
     * @dataProvider providerOfInvalidDayThrowsException
     * @expectedException \InvalidArgumentException
     *
     * @param integer $day
     */
    public function testOfInvalidDayThrowsException($day)
    {
        DayOfWeek::of($day);
    }

    /**
     * @return array
     */
    public function providerOfInvalidDayThrowsException()
    {
        return [
            [0],
            [8]
        ];
    }

    public function testConstants()
    {
        $this->assertSame(1, DayOfWeek::MONDAY);
        $this->assertSame(2, DayOfWeek::TUESDAY);
        $this->assertSame(3, DayOfWeek::WEDNESDAY);
        $this->assertSame(4, DayOfWeek::THURSDAY);
        $this->assertSame(5, DayOfWeek::FRIDAY);
        $this->assertSame(6, DayOfWeek::SATURDAY);
        $this->assertSame(7, DayOfWeek::SUNDAY);
    }

    /**
     * @dataProvider providerDayOfWeekFactoryMethods
     *
     * @param DayOfWeek $dayOfWeek    The DayOfWeek to test.
     * @param integer   $integerValue The ISO 8601 day of the week integer value expected.
     */
    public function testDayOfWeekFactoryMethods(DayOfWeek $dayOfWeek, $integerValue)
    {
        $this->assertEquals($integerValue, $dayOfWeek->getValue());
    }

    /**
     * @return array
     */
    public function providerDayOfWeekFactoryMethods()
    {
        return [
            [DayOfWeek::monday(), DayOfWeek::MONDAY],
            [DayOfWeek::tuesday(), DayOfWeek::TUESDAY],
            [DayOfWeek::wednesday(), DayOfWeek::WEDNESDAY],
            [DayOfWeek::thursday(), DayOfWeek::THURSDAY],
            [DayOfWeek::friday(), DayOfWeek::FRIDAY],
            [DayOfWeek::saturday(), DayOfWeek::SATURDAY],
            [DayOfWeek::sunday(), DayOfWeek::SUNDAY]
        ];
    }

    public function testGetAll()
    {
        for ($day = DayOfWeek::MONDAY; $day <= DayOfWeek::SUNDAY; $day++) {
            $expected = [];
            $actual = [];

            for ($i = 0; $i < 7; $i++) {
                $expected[] = (($day + $i - 1) % 7) + 1;
            }

            foreach (DayOfWeek::getAll(DayOfWeek::of($day)) as $dayOfWeek) {
                $actual[] = $dayOfWeek->getValue();
            }

            $this->assertTrue($actual === $expected);
        }
    }

    /**
     * @todo belongs to LocalDate tests
     *
     * @dataProvider providerGetDayOfWeekFromLocalDate
     *
     * @param string  $localDate The local date to test, as a string.
     * @param integer $dayOfWeek The day-of-week number that matches the local date.
     */
    public function testGetDayOfWeekFromLocalDate($localDate, $dayOfWeek)
    {
        $localDate = LocalDate::parse($localDate);
        $dayOfWeek = DayOfWeek::of($dayOfWeek);

        $this->assertTrue($localDate->getDayOfWeek()->isEqualTo($dayOfWeek));
    }

    /**
     * @return array
     */
    public function providerGetDayOfWeekFromLocalDate()
    {
        return [
            ['2000-01-01', DayOfWeek::SATURDAY],
            ['2001-01-01', DayOfWeek::MONDAY],
            ['2002-01-01', DayOfWeek::TUESDAY],
            ['2003-01-01', DayOfWeek::WEDNESDAY],
            ['2004-01-01', DayOfWeek::THURSDAY],
            ['2005-01-01', DayOfWeek::SATURDAY],
            ['2006-01-01', DayOfWeek::SUNDAY],
            ['2007-01-01', DayOfWeek::MONDAY],
            ['2008-01-01', DayOfWeek::TUESDAY],
            ['2009-01-01', DayOfWeek::THURSDAY],
            ['2010-01-01', DayOfWeek::FRIDAY],
            ['2011-01-01', DayOfWeek::SATURDAY],
            ['2012-01-01', DayOfWeek::SUNDAY],
        ];
    }

    /**
     * @dataProvider providerToString
     *
     * @param DayOfWeek $dayOfWeek The day-of-week to test.
     * @param string    $name      The expected name.
     */
    public function testToString(DayOfWeek $dayOfWeek, $name)
    {
        $this->assertSame($name, (string) $dayOfWeek);
    }

    public function testPlusMinusEntireWeeks()
    {
        foreach (DayOfWeek::getAll() as $dayOfWeek) {
            foreach ([-14, -7, 0, 7, 14] as $daysToAdd) {
                $this->assertTrue($dayOfWeek->plus($daysToAdd)->isEqualTo($dayOfWeek));
                $this->assertTrue($dayOfWeek->minus($daysToAdd)->isEqualTo($dayOfWeek));
            }
        }
    }

    /**
     * @dataProvider providerPlusDays
     *
     * @param integer $base     The base week day number.
     * @param integer $amount   The amount of days to add.
     * @param integer $expected The expected week day number.
     */
    public function testPlusDays($base, $amount, $expected)
    {
        $base = DayOfWeek::of($base);
        $expected = DayOfWeek::of($expected);

        $this->assertTrue($base->plus($amount)->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerPlusDays()
    {
        return [
            [1, -8, 7],
            [1, -7, 1],
            [1, -6, 2],
            [1, -5, 3],
            [1, -4, 4],
            [1, -3, 5],
            [1, -2, 6],
            [1, -1, 7],
            [1, 0, 1],
            [1, 1, 2],
            [1, 2, 3],
            [1, 3, 4],
            [1, 4, 5],
            [1, 5, 6],
            [1, 6, 7],
            [1, 7, 1],
            [1, 8, 2],

            [1, 1, 2],
            [2, 1, 3],
            [3, 1, 4],
            [4, 1, 5],
            [5, 1, 6],
            [6, 1, 7],
            [7, 1, 1],

            [1, -1, 7],
            [2, -1, 1],
            [3, -1, 2],
            [4, -1, 3],
            [5, -1, 4],
            [6, -1, 5],
            [7, -1, 6],
        ];
    }

    /**
     * @dataProvider providerMinusDays
     *
     * @param integer $base     The base week day number.
     * @param integer $amount   The amount of days to subtract.
     * @param integer $expected The expected week day number.
     */
    public function testMinusDays($base, $amount, $expected)
    {
        $base = DayOfWeek::of($base);
        $expected = DayOfWeek::of($expected);

        $this->assertTrue($base->minus($amount)->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerMinusDays()
    {
        return [
            [1, -8, 2],
            [1, -7, 1],
            [1, -6, 7],
            [1, -5, 6],
            [1, -4, 5],
            [1, -3, 4],
            [1, -2, 3],
            [1, -1, 2],
            [1, 0, 1],
            [1, 1, 7],
            [1, 2, 6],
            [1, 3, 5],
            [1, 4, 4],
            [1, 5, 3],
            [1, 6, 2],
            [1, 7, 1],
            [1, 8, 7],
        ];
    }

    /**
     * @return array
     */
    public function providerToString()
    {
        return [
            [DayOfWeek::monday(), 'MONDAY'],
            [DayOfWeek::tuesday(),'TUESDAY'],
            [DayOfWeek::wednesday(), 'WEDNESDAY'],
            [DayOfWeek::thursday(), 'THURSDAY'],
            [DayOfWeek::friday(), 'FRIDAY'],
            [DayOfWeek::saturday(), 'SATURDAY'],
            [DayOfWeek::sunday(), 'SUNDAY']
        ];
    }
}
