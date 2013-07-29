<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\LocalTime;

/**
 * Unit tests for class LocalTime.
 */
class LocalTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testMidnightFactoryMethod()
    {
        $time = LocalTime::midnight();

        $this->assertEquals($time->getHour(), 0);
        $this->assertEquals($time->getMinute(), 0);
        $this->assertEquals($time->getSecond(), 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testCreateFromSecondOfDayTooLowShouldThrowException()
    {
        LocalTime::ofSecondOfDay(-1);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     */
    public function testCreateFromSecondOfDayTooHighShouldThrowException()
    {
        LocalTime::ofSecondOfDay(86400);
    }

    /**
     * @dataProvider secondOfDayDataProvider
     *
     * @param int $secondOfDay
     * @param int $expectedHour
     * @param int $expectedMinute
     * @param int $expectedSecond
     */
    public function testCreateFromSecondOfDay($secondOfDay, $expectedHour, $expectedMinute, $expectedSecond)
    {
        $actual = LocalTime::ofSecondOfDay($secondOfDay);
        $expected = LocalTime::of($expectedHour, $expectedMinute, $expectedSecond);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * @return array
     */
    public function secondOfDayDataProvider()
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
     * @dataProvider parseDataProvider
     *
     * @param string $string
     * @param int    $hour
     * @param int    $minute
     * @param int    $second
     */
    public function testParse($string, $hour, $minute, $second)
    {
        $actual = LocalTime::parse($string);
        $expected = LocalTime::of($hour, $minute, $second);

        $this->assertTrue($actual->isEqualTo($expected));
    }

    /**
     * Data provider for testParse().
     * The test strings include extra blank / non-numeric characters on purpose.
     *
     * @return array
     */
    public function parseDataProvider()
    {
        return [
            ['12:34', 12, 34, 0],
            ['12:34:56', 12, 34, 56]
        ];
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     */
    public function testParseInvalidStringShouldThrowException()
    {
        LocalTime::parse('12');
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     * @return void
     */
    public function testCreateTimeWithHourTooLowShouldThrowException()
    {
        LocalTime::of(-1, 0, 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     * @return void
     */
    public function testCreateTimeWithHourTooHighShouldThrowException()
    {
        LocalTime::of(24, 0, 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     * @return void
     */
    public function testCreateTimeWithMinuteTooLowShouldThrowException()
    {
        LocalTime::of(0, -1, 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     * @return void
     */
    public function testCreateTimeWithMinuteTooHighShouldThrowException()
    {
        LocalTime::of(0, 60, 0);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     * @return void
     */
    public function testCreateTimeWithSecondTooLowShouldThrowException()
    {
        LocalTime::of(0, 0, -1);
    }

    /**
     * @expectedException \Brick\DateTime\DateTimeException
     * @return void
     */
    public function testCreateTimeWithSecondTooHighShouldThrowException()
    {
        LocalTime::of(0, 0, 60);
    }

    /**
     * @return void
     */
    public function testGetHourMinuteSecond()
    {
        $date = LocalTime::of(23, 29, 59);

        $this->assertEquals(23, $date->getHour());
        $this->assertEquals(29, $date->getMinute());
        $this->assertEquals(59, $date->getSecond());
    }

    /**
     * @return void
     */
    public function testCompare()
    {
        $a = LocalTime::of(12, 30, 45);
        $b = LocalTime::of(23, 30, 00);

        $this->assertLessThan(0, $a->compareTo($b));
        $this->assertGreaterThan(0, $b->compareTo($a));
        $this->assertEquals(0, $a->compareTo($a));
        $this->assertEquals(0, $b->compareTo($b));
    }

    /**
     * @dataProvider toIntegerDataProvider
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $result
     * @return void
     */
    public function testToInteger($hour, $minute, $second, $result)
    {
        $time = LocalTime::of($hour, $minute, $second);
        $this->assertEquals($result, $time->toSecondOfDay());
    }

    /**
     * @return array
     */
    public function toIntegerDataProvider()
    {
        return array(
            array(0, 0, 0, 0),
            array(1, 0, 0, 3600),
            array(0, 1, 0, 60),
            array(0, 0, 1, 1),
            array(12, 34, 56, 45296),
            array(23, 59, 59, 86399)
        );
    }

    /**
     * @return void
     */
    public function testCastToString()
    {
        $time = LocalTime::of(12, 34, 56);
        $this->assertEquals('12:34:56', (string) $time);
    }

    /**
     * @dataProvider plusHoursDataProvider
     *
     * @param int $hoursToAdd
     * @param int $expectedHour
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
    public function plusHoursDataProvider()
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
     * @dataProvider plusMinutesDataProvider
     *
     * @param int $minutesToAdd
     * @param int $expectedHour
     * @param int $expectedMinute
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
    public function plusMinutesDataProvider()
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
     * @dataProvider plusSecondsDataProvider
     *
     * @param int $secondsToAdd
     * @param int $expectedHour
     * @param int $expectedMinute
     * @param int $expectedSecond
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
    public function plusSecondsDataProvider()
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
     * @dataProvider minusHoursDataProvider
     *
     * @param int $hoursToSubtract
     * @param int $expectedHour
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
    public function minusHoursDataProvider()
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
     * @dataProvider minusMinutesDataProvider
     *
     * @param int $minutesToSubtract
     * @param int $expectedHour
     * @param int $expectedMinute
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
    public function minusMinutesDataProvider()
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
     * @dataProvider minusSecondsDataProvider
     *
     * @param int $secondsToSubtract
     * @param int $expectedHour
     * @param int $expectedMinute
     * @param int $expectedSecond
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
    public function minusSecondsDataProvider()
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

    /**
     * @return void
     */
    public function testMinMaxOf()
    {
        $a = LocalTime::of(11, 45);
        $b = LocalTime::of(14, 30);
        $c = LocalTime::of(17, 15);

        $this->assertTrue(LocalTime::minOf([$a, $b, $c])->isEqualTo($a));
        $this->assertTrue(LocalTime::maxOf([$a, $b, $c])->isEqualTo($c));
    }
}
