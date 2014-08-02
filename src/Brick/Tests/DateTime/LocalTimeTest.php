<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalTime;

/**
 * Unit tests for class LocalTime.
 */
class LocalTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testMidnight()
    {
        $time = LocalTime::midnight();

        $this->assertSame(0, $time->getHour());
        $this->assertSame(0, $time->getMinute());
        $this->assertSame(0, $time->getSecond());
    }

    /**
     * @dataProvider providerOfInvalidSecondOfDayThrowsException
     * @expectedException \Brick\DateTime\DateTimeException
     *
     * @param integer $secondOfDay
     */
    public function testOfInvalidSecondOfDayThrowsException($secondOfDay)
    {
        LocalTime::ofSecondOfDay($secondOfDay);
    }

    /**
     * @return array
     */
    public function providerOfInvalidSecondOfDayThrowsException()
    {
        return [
            [-1],
            [86400]
        ];
    }

    /**
     * @dataProvider providerOfSecondOfDay
     *
     * @param integer $secondOfDay
     * @param integer $expectedHour
     * @param integer $expectedMinute
     * @param integer $expectedSecond
     */
    public function testOfSecondOfDay($secondOfDay, $expectedHour, $expectedMinute, $expectedSecond)
    {
        $localTime = LocalTime::ofSecondOfDay($secondOfDay);

        $this->assertSame($expectedHour, $localTime->getHour());
        $this->assertSame($expectedMinute, $localTime->getMinute());
        $this->assertSame($expectedSecond, $localTime->getSecond());
    }

    /**
     * @return array
     */
    public function providerOfSecondOfDay()
    {
        return [
            [0, 0, 0, 0],
            [1, 0, 0, 1],
            [59, 0, 0, 59],
            [60, 0, 1, 0],
            [61, 0, 1, 1],
            [3599, 0, 59, 59],
            [3600, 1, 0, 0],
            [3601, 1, 0, 1],
            [3659, 1, 0, 59],
            [3660, 1, 1, 0],
            [3661, 1, 1, 1],
            [43199, 11, 59, 59],
            [43200, 12, 0, 0],
            [43201, 12, 0, 1],
            [86399, 23, 59, 59]
        ];
    }

    /**
     * @dataProvider providerParse
     *
     * @param string  $string
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     */
    public function testParse($string, $hour, $minute, $second)
    {
        $actual = LocalTime::parse($string);
        $expected = LocalTime::of($hour, $minute, $second);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['12:34', 12, 34, 0],
            ['12:34:56', 12, 34, 56]
        ];
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     * @dataProvider providerParseInvalidStringThrowsException
     *
     * @param string $string
     */
    public function testParseInvalidStringThrowsException($string)
    {
        LocalTime::parse('12');
    }

    /**
     * @return array
     */
    public function providerParseInvalidStringThrowsException()
    {
        return [
            ['12'],
            ['12.34'],
            [' 12:34'],
            ['12:34:56 '],
        ];
    }

    /**
     * @dataProvider providerOfInvalidTimeThrowsException
     * @expectedException \Brick\DateTime\DateTimeException
     *
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     */
    public function testOfInvalidTimeThrowsException($hour, $minute, $second)
    {
        LocalTime::of($hour, $minute, $second);
    }

    /**
     * @return array
     */
    public function providerOfInvalidTimeThrowsException()
    {
        return [
            [-1, 0, 0],
            [24, 0, 0],
            [0, -1, 0],
            [0, 60, 0],
            [0, 0, -1],
            [0, 0, 60]
        ];
    }

    public function testGetHourMinuteSecond()
    {
        $date = LocalTime::of(23, 29, 59);

        $this->assertSame(23, $date->getHour());
        $this->assertSame(29, $date->getMinute());
        $this->assertSame(59, $date->getSecond());
    }

    public function testCompare()
    {
        $a = LocalTime::of(12, 30, 45);
        $b = LocalTime::of(23, 30, 00);

        $this->assertLessThan(0, $a->compareTo($b));
        $this->assertGreaterThan(0, $b->compareTo($a));
        $this->assertSame(0, $a->compareTo($a));
        $this->assertSame(0, $b->compareTo($b));
    }

    /**
     * @dataProvider providerToInteger
     *
     * @param integer $hour
     * @param integer $minute
     * @param integer $second
     * @param integer $result
     */
    public function testToInteger($hour, $minute, $second, $result)
    {
        $time = LocalTime::of($hour, $minute, $second);
        $this->assertEquals($result, $time->toSecondOfDay());
    }

    /**
     * @return array
     */
    public function providerToInteger()
    {
        return [
            [0, 0, 0, 0],
            [1, 0, 0, 3600],
            [0, 1, 0, 60],
            [0, 0, 1, 1],
            [12, 34, 56, 45296],
            [23, 59, 59, 86399]
        ];
    }

    public function testCastToString()
    {
        $time = LocalTime::of(12, 34, 56);
        $this->assertEquals('12:34:56', (string) $time);
    }

    /**
     * @dataProvider providerPlusHours
     *
     * @param integer $hoursToAdd
     * @param integer $expectedHour
     */
    public function testPlusHours($hoursToAdd, $expectedHour)
    {
        $time = LocalTime::of(14, 0);

        $actual = $time->plusHours($hoursToAdd);
        $expected = LocalTime::of($expectedHour, 0);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerPlusHours()
    {
        return [
            [-25, 13],
            [-24, 14],
            [-23, 15],
            [-15, 23],
            [-14, 0],
            [-13, 1],
            [-1, 13],
            [0, 14],
            [1, 15],
            [9, 23],
            [10, 0],
            [11, 1],
            [23, 13],
            [24, 14],
            [25, 15]
        ];
    }

    /**
     * @dataProvider providerPlusMinutes
     *
     * @param integer $minutesToAdd
     * @param integer $expectedHour
     * @param integer $expectedMinute
     */
    public function testPlusMinutes($minutesToAdd, $expectedHour, $expectedMinute)
    {
        $time = LocalTime::of(12, 45);

        $actual = $time->plusMinutes($minutesToAdd);
        $expected = LocalTime::of($expectedHour, $expectedMinute);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerPlusMinutes()
    {
        return [
            [-1441, 12, 44],
            [-1440, 12, 45],
            [-1439, 12, 46],
            [-766, 23, 59],
            [-765, 0, 0],
            [-764, 0, 1],
            [-1, 12, 44],
            [0, 12, 45],
            [1, 12, 46],
            [674, 23, 59],
            [675, 0, 0],
            [676, 0, 1],
            [1439, 12, 44],
            [1440, 12, 45],
            [1441, 12, 46]
        ];
    }

    /**
     * @dataProvider providerPlusSeconds
     *
     * @param integer $secondsToAdd
     * @param integer $expectedHour
     * @param integer $expectedMinute
     * @param integer $expectedSecond
     */
    public function testPlusSeconds($secondsToAdd, $expectedHour, $expectedMinute, $expectedSecond)
    {
        $time = LocalTime::of(15, 30, 45);

        $actual = $time->plusSeconds($secondsToAdd);
        $expected = LocalTime::of($expectedHour, $expectedMinute, $expectedSecond);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerPlusSeconds()
    {
        return [
            [-86401, 15, 30, 44],
            [-86400, 15, 30, 45],
            [-86399, 15, 30, 46],
            [-55846, 23, 59, 59],
            [-55845, 0, 0, 0],
            [-55844, 0, 0, 1],
            [-1, 15, 30, 44],
            [0, 15, 30, 45],
            [1, 15, 30, 46],
            [30554, 23, 59, 59],
            [30555, 0, 0, 0],
            [30556, 0, 0, 1],
            [86399, 15, 30, 44],
            [86400, 15, 30, 45],
            [86401, 15, 30, 46]
        ];
    }

    /**
     * @dataProvider providerMinusHours
     *
     * @param integer $hoursToSubtract
     * @param integer $expectedHour
     */
    public function testMinusHours($hoursToSubtract, $expectedHour)
    {
        $time = LocalTime::of(14, 0);

        $actual = $time->minusHours($hoursToSubtract);
        $expected = LocalTime::of($expectedHour, 0);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerMinusHours()
    {
        return [
            [-25, 15],
            [-24, 14],
            [-23, 13],
            [-11, 1],
            [-10, 0],
            [-9, 23],
            [-1, 15],
            [0, 14],
            [1, 13],
            [13, 1],
            [14, 0],
            [15, 23],
            [23, 15],
            [24, 14],
            [25, 13]
        ];
    }

    /**
     * @dataProvider providerMinusMinutes
     *
     * @param integer $minutesToSubtract
     * @param integer $expectedHour
     * @param integer $expectedMinute
     */
    public function testMinusMinutes($minutesToSubtract, $expectedHour, $expectedMinute)
    {
        $time = LocalTime::of(12, 45);

        $actual = $time->minusMinutes($minutesToSubtract);
        $expected = LocalTime::of($expectedHour, $expectedMinute);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerMinusMinutes()
    {
        return [
            [-1441, 12, 46],
            [-1440, 12, 45],
            [-1439, 12, 44],
            [-676, 0, 1],
            [-675, 0, 0],
            [-674, 23, 59],
            [-1, 12, 46],
            [0, 12, 45],
            [1, 12, 44],
            [764, 0, 1],
            [765, 0, 0],
            [766, 23, 59],
            [1439, 12, 46],
            [1440, 12, 45],
            [1441, 12, 44]
        ];
    }

    /**
     * @dataProvider providerMinusSeconds
     *
     * @param integer $secondsToSubtract
     * @param integer $expectedHour
     * @param integer $expectedMinute
     * @param integer $expectedSecond
     */
    public function testMinusSeconds($secondsToSubtract, $expectedHour, $expectedMinute, $expectedSecond)
    {
        $time = LocalTime::of(15, 30, 45);

        $actual = $time->minusSeconds($secondsToSubtract);
        $expected = LocalTime::of($expectedHour, $expectedMinute, $expectedSecond);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function providerMinusSeconds()
    {
        return [
            [-86401, 15, 30, 46],
            [-86400, 15, 30, 45],
            [-86399, 15, 30, 44],
            [-30556, 0, 0, 1],
            [-30555, 0, 0, 0],
            [-30554, 23, 59, 59],
            [-1, 15, 30, 46],
            [0, 15, 30, 45],
            [1, 15, 30, 44],
            [55844, 0, 0, 1],
            [55845, 0, 0, 0],
            [55846, 23, 59, 59],
            [86399, 15, 30, 46],
            [86400, 15, 30, 45],
            [86401, 15, 30, 44]
        ];
    }

    public function testMinMaxOf()
    {
        $a = LocalTime::of(11, 45);
        $b = LocalTime::of(14, 30);
        $c = LocalTime::of(17, 15);

        $this->assertTrue(LocalTime::minOf([$a, $b, $c])->isEqualTo($a));
        $this->assertTrue(LocalTime::maxOf([$a, $b, $c])->isEqualTo($c));
    }
}
