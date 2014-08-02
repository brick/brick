<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Weekday;
use Brick\DateTime\LocalDate;

/**
 * Unit tests for class Weekday.
 */
class WeekdayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerOfInvalidDayThrowsException
     * @expectedException \UnexpectedValueException
     *
     * @param integer $day
     */
    public function testOfInvalidDayThrowsException($day)
    {
        Weekday::of($day);
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
        $this->assertSame(1, Weekday::MONDAY);
        $this->assertSame(2, Weekday::TUESDAY);
        $this->assertSame(3, Weekday::WEDNESDAY);
        $this->assertSame(4, Weekday::THURSDAY);
        $this->assertSame(5, Weekday::FRIDAY);
        $this->assertSame(6, Weekday::SATURDAY);
        $this->assertSame(7, Weekday::SUNDAY);
    }

    /**
     * @dataProvider providerWeekdayFactoryMethods
     *
     * @param Weekday $weekday      The Weekday to test.
     * @param integer $integerValue The ISO 8601 day of the week integer value expected.
     */
    public function testWeekdayFactoryMethods(Weekday $weekday, $integerValue)
    {
        $this->assertEquals($integerValue, $weekday->getValue());
    }

    /**
     * @return array
     */
    public function providerWeekdayFactoryMethods()
    {
        return [
            [Weekday::monday(), Weekday::MONDAY],
            [Weekday::tuesday(), Weekday::TUESDAY],
            [Weekday::wednesday(), Weekday::WEDNESDAY],
            [Weekday::thursday(), Weekday::THURSDAY],
            [Weekday::friday(), Weekday::FRIDAY],
            [Weekday::saturday(), Weekday::SATURDAY],
            [Weekday::sunday(), Weekday::SUNDAY]
        ];
    }

    public function testGetWeekdays()
    {
        for ($day = Weekday::MONDAY; $day <= Weekday::SUNDAY; $day++) {
            $expected = [];
            $actual = [];

            for ($i = 0; $i < 7; $i++) {
                $expected[] = (($day + $i - 1) % 7) + 1;
            }

            foreach (Weekday::getWeekdays(Weekday::of($day)) as $weekday) {
                $actual[] = $weekday->getValue();
            }

            $this->assertTrue($actual === $expected);
        }
    }

    /**
     * @dataProvider providerGetWeekdayFromLocalDate
     *
     * @param string  $localDate The local date to test, as a string.
     * @param integer $weekday   The day-of-week number that matches the local date.
     */
    public function testGetWeekdayFromLocalDate($localDate, $weekday)
    {
        $localDate = LocalDate::parse($localDate);
        $weekday = Weekday::of($weekday);

        $this->assertTrue($localDate->getDayOfWeek()->isEqualTo($weekday));
    }

    /**
     * @return array
     */
    public function providerGetWeekdayFromLocalDate()
    {
        return [
            ['2000-01-01', Weekday::SATURDAY],
            ['2001-01-01', Weekday::MONDAY],
            ['2002-01-01', Weekday::TUESDAY],
            ['2003-01-01', Weekday::WEDNESDAY],
            ['2004-01-01', Weekday::THURSDAY],
            ['2005-01-01', Weekday::SATURDAY],
            ['2006-01-01', Weekday::SUNDAY],
            ['2007-01-01', Weekday::MONDAY],
            ['2008-01-01', Weekday::TUESDAY],
            ['2009-01-01', Weekday::THURSDAY],
            ['2010-01-01', Weekday::FRIDAY],
            ['2011-01-01', Weekday::SATURDAY],
            ['2012-01-01', Weekday::SUNDAY],
        ];
    }

    /**
     * @dataProvider providerGetName
     *
     * @param Weekday $weekday
     * @param string  $name
     */
    public function testGetName(Weekday $weekday, $name)
    {
        $this->assertEquals($weekday->getName(), $name);
    }

    /**
     * @return array
     */
    public function providerGetName()
    {
        return [
            [Weekday::monday(), 'Monday'],
            [Weekday::tuesday(),'Tuesday'],
            [Weekday::wednesday(), 'Wednesday'],
            [Weekday::thursday(), 'Thursday'],
            [Weekday::friday(), 'Friday'],
            [Weekday::saturday(), 'Saturday'],
            [Weekday::sunday(), 'Sunday']
        ];
    }

    public function testPlusMinusEntireWeeks()
    {
        foreach (Weekday::getWeekdays() as $weekday) {
            foreach ([-14, -7, 0, 7, 14] as $daysToAdd) {
                $this->assertTrue($weekday->plus($daysToAdd)->isEqualTo($weekday));
                $this->assertTrue($weekday->minus($daysToAdd)->isEqualTo($weekday));
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
        $base = Weekday::of($base);
        $expected = Weekday::of($expected);

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
        $base = Weekday::of($base);
        $expected = Weekday::of($expected);

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
}
