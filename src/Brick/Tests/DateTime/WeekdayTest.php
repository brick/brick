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
     * @expectedException \UnexpectedValueException
     * @return void
     */
    public function testFactoryThrowsExceptionOnWeekdayTooLow()
    {
        Weekday::of(0);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @return void
     */
    public function testFactoryThrowsExceptionOnWeekdayTooHigh()
    {
        Weekday::of(8);
    }

    /**
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals(1, Weekday::MONDAY);
        $this->assertEquals(2, Weekday::TUESDAY);
        $this->assertEquals(3, Weekday::WEDNESDAY);
        $this->assertEquals(4, Weekday::THURSDAY);
        $this->assertEquals(5, Weekday::FRIDAY);
        $this->assertEquals(6, Weekday::SATURDAY);
        $this->assertEquals(7, Weekday::SUNDAY);
    }

    /**
     * @dataProvider weekdayFactoryMethodsProvider
     *
     * @param  \Brick\DateTime\Weekday $weekday      The Weekday to test.
     * @param  int                      $integerValue The ISO 8601 day of the week integer value expected.
     * @return void
     */
    public function testWeekdayFactoryMethods(Weekday $weekday, $integerValue)
    {
        $this->assertEquals($integerValue, $weekday->getValue());
    }

    /**
     * @return array
     */
    public function weekdayFactoryMethodsProvider()
    {
        return array(
            array(Weekday::monday(), Weekday::MONDAY),
            array(Weekday::tuesday(), Weekday::TUESDAY),
            array(Weekday::wednesday(), Weekday::WEDNESDAY),
            array(Weekday::thursday(), Weekday::THURSDAY),
            array(Weekday::friday(), Weekday::FRIDAY),
            array(Weekday::saturday(), Weekday::SATURDAY),
            array(Weekday::sunday(), Weekday::SUNDAY)
        );
    }

    /**
     * @return void
     */
    public function testGetWeekdays()
    {
        for ($day = Weekday::MONDAY; $day <= Weekday::SUNDAY; $day++) {
            $expected = array();
            $actual = array();

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
     * @dataProvider getWeekdayFromLocalDateProvider
     *
     * @param string $localDate The local date to test, as a string.
     * @param int    $weekday   The day-of-week number that matches the local date.
     * @return void
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
    public function getWeekdayFromLocalDateProvider()
    {
        return array(
            array('2000-01-01', Weekday::SATURDAY),
            array('2001-01-01', Weekday::MONDAY),
            array('2002-01-01', Weekday::TUESDAY),
            array('2003-01-01', Weekday::WEDNESDAY),
            array('2004-01-01', Weekday::THURSDAY),
            array('2005-01-01', Weekday::SATURDAY),
            array('2006-01-01', Weekday::SUNDAY),
            array('2007-01-01', Weekday::MONDAY),
            array('2008-01-01', Weekday::TUESDAY),
            array('2009-01-01', Weekday::THURSDAY),
            array('2010-01-01', Weekday::FRIDAY),
            array('2011-01-01', Weekday::SATURDAY),
            array('2012-01-01', Weekday::SUNDAY),
        );
    }

    /**
     * @dataProvider getNameProvider
     *
     * @param  \Brick\DateTime\Weekday $weekday
     * @param  string                                   $name
     * @return void
     */
    public function testGetName(Weekday $weekday, $name)
    {
        $this->assertEquals($weekday->getName(), $name);
    }

    /**
     * @return array
     */
    public function getNameProvider()
    {
        return array(
            array(Weekday::monday(), 'Monday'),
            array(Weekday::tuesday(),'Tuesday'),
            array(Weekday::wednesday(), 'Wednesday'),
            array(Weekday::thursday(), 'Thursday'),
            array(Weekday::friday(), 'Friday'),
            array(Weekday::saturday(), 'Saturday'),
            array(Weekday::sunday(), 'Sunday')
        );
    }

    /**
     * @return void
     */
    public function testPlusMinusEntireWeeks()
    {
        foreach (Weekday::getWeekdays() as $weekday) {
            foreach (array (-14, -7, 0, 7, 14) as $daysToAdd) {
                $this->assertTrue($weekday->plus($daysToAdd)->isEqualTo($weekday));
                $this->assertTrue($weekday->minus($daysToAdd)->isEqualTo($weekday));
            }
        }
    }

    /**
     * @dataProvider plusDaysProvider
     *
     * @param int $base     The base week day number.
     * @param int $amount   The amount of days to add.
     * @param int $expected The expected week day number.
     * @return void
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
    public function plusDaysProvider()
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
     * @dataProvider minusDaysProvider
     *
     * @param int $base     The base week day number.
     * @param int $amount   The amount of days to subtract.
     * @param int $expected The expected week day number.
     * @return void
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
    public function minusDaysProvider()
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
