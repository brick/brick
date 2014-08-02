<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Duration;
use Brick\DateTime\Instant;

/**
 * Unit tests for class Duration.
 */
class DurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Duration $expected
     * @param Duration $actual
     */
    private function assertDurationEquals(Duration $expected, Duration $actual)
    {
        $this->assertTrue($expected->isEqualTo($actual), sprintf(
            'Failed asserting that %s equals to %s',
            $actual->toString(),
            $expected->toString()
        ));
    }

    public function testZero()
    {
        $zero = Duration::zero();

        $this->assertSame(0, $zero->getSeconds());
        $this->assertSame(0, $zero->getMicroseconds());
    }

    /**
     * @dataProvider providerOfSeconds
     *
     * @param integer $seconds              The duration in seconds.
     * @param integer $microAdjustment      The microseconds adjustement to the duration.
     * @param integer $expectedSeconds      The expected adjusted duration seconds.
     * @param integer $expectedMicroseconds The expected adjusted duration microseconds.
     */
    public function testOfSeconds($seconds, $microAdjustment, $expectedSeconds, $expectedMicroseconds)
    {
        $duration = Duration::ofSeconds($seconds, $microAdjustment);

        $this->assertSame($expectedSeconds, $duration->getSeconds());
        $this->assertSame($expectedMicroseconds, $duration->getMicroseconds());
    }

    /**
     * @return array
     */
    public function providerOfSeconds()
    {
        return [
            [3, 1, 3, 1],
            [4, -999999, 3, 1],
            [2, 1000001, 3, 1],
            [-3, 1, -3, 1],
            [-4, 1000001, -3, 1],
            [-2, -999999, -3, 1],
            [1, -1000001, -1, 999999],
            [-1, -1000001, -3, 999999]
        ];
    }

    public function testOfMinutes()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i * 60, Duration::ofMinutes($i)->getSeconds());
        }
    }

    public function testOfHours()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i * 3600, Duration::ofHours($i)->getSeconds());
        }
    }

    public function testOfDays()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i * 86400, Duration::ofDays($i)->getSeconds());
        }
    }

    /**
     * @dataProvider providerBetween
     *
     * @param integer $seconds1
     * @param integer $micros1
     * @param integer $seconds2
     * @param integer $micros2
     * @param integer $seconds
     * @param integer $micros
     */
    public function testBetween($seconds1, $micros1, $seconds2, $micros2, $seconds, $micros)
    {
        $duration = Duration::between(
            Instant::of($seconds1, $micros1),
            Instant::of($seconds2, $micros2)
        );

        $this->assertSame($seconds, $duration->getSeconds());
        $this->assertSame($micros, $duration->getMicroseconds());
    }

    /**
     * @return array
     */
    public function providerBetween()
    {
        return [
            [0, 0, 0, 0, 0, 0],
            [3, 0, 7, 0, 4, 0],
            [7, 0, 3, 0, -4, 0],

            [0, 500000, 1, 500000, 1, 0],
            [0, 500000, 1, 750000, 1, 250000],
            [0, 500000, 1, 250000, 0, 750000],

            [-1, 500000, 0, 0, 0, 500000],
            [-1, 500000, 0, 500000, 1, 0],
            [-1, 999999, 2, 2, 2, 3],

            [0, 0, -1, 500000, -1, 500000],
            [0, 500000, -1, 500000, -1, 0],
            [2, 2, -1, 999999, -3, 999997],
        ];
    }

    /**
     * @dataProvider providerParse
     *
     * @param string  $text
     * @param integer $seconds
     * @param integer $microseconds
     */
    public function testParse($text, $seconds, $microseconds)
    {
        $duration = Duration::parse($text);

        $this->assertSame($seconds, $duration->getSeconds());
        $this->assertSame($microseconds, $duration->getMicroseconds());
    }

    /**
     * @return array
     */
    public function providerParse()
    {
        return [
            ['PT0S', 0, 0],
            ['pT-0S', 0, 0],
            ['Pt0S', 0, 0],
            ['PT-0s', 0, 0],

            ['PT0.1S', 0, 100000],
            ['PT-0.1S', -1, 900000],

            ['PT1.001S', 1, 1000],
            ['PT-1.001S', -2, 999000],
            ['PT-1.999S', -2, 1000],

            ['PT0.000001S', 0, 1],
            ['PT-0.000001S', -1, 999999],
            ['PT-0.999999S', -1, 1],

            ['PT0.0000009S', 0, 0],
            ['PT1.0000009S', 1, 0],

            ['PT-0.0000009S', 0, 0],
            ['PT-1.0000009S', -1, 0],

            ['PT-0.999999999S', -1, 1],
            ['PT-1.1000009S', -2, 900000],

            ['PT1S', 1, 0],
            ['PT12S', 12, 0],
            ['PT123456789S', 123456789, 0],

            ['PT-1S', -1, 0],
            ['PT-12S', -12, 0],
            ['PT-123456789S', -123456789, 0],
        ];
    }

    /**
     * @dataProvider providerParseFailureThrowsException
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     *
     * @param string $text
     */
    public function testParseFailureThrowsException($text)
    {
        Duration::parse($text);
    }

    /**
     * @return array
     */
    public function providerParseFailureThrowsException()
    {
        return [
            [''],
            ['PTS'],
            ['XT0S'],
            ['PX0S'],
            ['PT0X'],

            ['XPT0S'],
            ['PT0SX'],

            ['PT+S'],
            ['PT-S'],
            ['PT.S'],
            ['PTAS'],

            ['PT+0S'],
            ['PT+00S'],
            ['PT+000S'],
            ['PT-00S'],
            ['PT-000S'],
            ['PT+1S'],
            ['PT-.S'],
            ['PT+.S'],

            ['PT1X2S'],
            ['PT1.S'],
            ['PT.1S']
        ];
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     */
    public function testParsePositiveOutOfRange()
    {
        Duration::parse('PT' . PHP_INT_MAX . '1S');
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     */
    public function testParseNegativeOutOfRange()
    {
        Duration::parse('PT' . ~ PHP_INT_MAX . '1S');
    }

    public function testIsZero()
    {
        $this->assertFalse(Duration::ofSeconds(-1)->isZero());
        $this->assertTrue(Duration::ofSeconds(0)->isZero());
        $this->assertFalse(Duration::ofSeconds(1)->isZero());
    }

    public function testIsPositive()
    {
        $this->assertFalse(Duration::ofSeconds(-1)->isPositive());
        $this->assertFalse(Duration::ofSeconds(0)->isPositive());
        $this->assertTrue(Duration::ofSeconds(1)->isPositive());
    }

    public function testIsNegative()
    {
        $this->assertTrue(Duration::ofSeconds(-1)->isNegative());
        $this->assertFalse(Duration::ofSeconds(0)->isNegative());
        $this->assertFalse(Duration::ofSeconds(1)->isNegative());
    }

    /**
     * @dataProvider providerCompareTo
     *
     * @param integer $seconds1 The seconds of the 1st duration.
     * @param integer $micros1  The microseconds of the 1st duration.
     * @param integer $seconds2 The seconds of the 2nd duration.
     * @param integer $micros2  The microseconds of the 2nd duration.
     * @param integer $expected The expected return value.
     */
    public function testCompareTo($seconds1, $micros1, $seconds2, $micros2, $expected)
    {
        $duration1 = Duration::ofSeconds($seconds1, $micros1);
        $duration2 = Duration::ofSeconds($seconds2, $micros2);

        $this->assertSame($expected, $duration1->compareTo($duration2));
    }

    /**
     * @return array
     */
    public function providerCompareTo()
    {
        return [
            [-1, -1, -1, -1, 0],
            [-1, -1, -1, 0, -1],
            [-1, -1, 0, -1, -1],
            [-1, -1, 0, 0, -1],
            [-1, -1, 0, 1, -1],
            [-1, -1, 1, 0, -1],
            [-1, -1, 1, 1, -1],
            [-1, 0, -1, -1, 1],
            [-1, 0, -1, 0, 0],
            [-1, 0, 0, -1, -1],
            [-1, 0, 0, 0, -1],
            [-1, 0, 0, 1, -1],
            [-1, 0, 1, 0, -1],
            [-1, 0, 1, 1, -1],
            [0, -1, -1, -1, 1],
            [0, -1, -1, 0, 1],
            [0, -1, 0, -1, 0],
            [0, -1, 0, 0, -1],
            [0, -1, 0, 1, -1],
            [0, -1, 1, 0, -1],
            [0, -1, 1, 1, -1],
            [0, 0, -1, -1, 1],
            [0, 0, -1, 0, 1],
            [0, 0, 0, -1, 1],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 1, -1],
            [0, 0, 1, 0, -1],
            [0, 0, 1, 1, -1],
            [0, 1, -1, -1, 1],
            [0, 1, -1, 0, 1],
            [0, 1, 0, -1, 1],
            [0, 1, 0, 0, 1],
            [0, 1, 0, 1, 0],
            [0, 1, 1, 0, -1],
            [0, 1, 1, 1, -1],
            [1, 0, -1, -1, 1],
            [1, 0, -1, 0, 1],
            [1, 0, 0, -1, 1],
            [1, 0, 0, 0, 1],
            [1, 0, 0, 1, 1],
            [1, 0, 1, 0, 0],
            [1, 0, 1, 1, -1],
            [1, 1, -1, -1, 1],
            [1, 1, -1, 0, 1],
            [1, 1, 0, -1, 1],
            [1, 1, 0, 0, 1],
            [1, 1, 0, 1, 1],
            [1, 1, 1, 0, 1],
            [1, 1, 1, 1, 0]
        ];
    }

    /**
     * @dataProvider providerPlus
     *
     * @param integer $s1              The 1st duration's seconds.
     * @param integer $m1              The 1st duration's milliseconds.
     * @param integer $s2              The 2nd duration's seconds.
     * @param integer $m2              The 2nd duration's milliseconds.
     * @param integer $expectedSeconds The expected seconds.
     * @param integer $expectedMicros  The expected milliseconds.
     */
    public function testPlus($s1, $m1, $s2, $m2, $expectedSeconds, $expectedMicros)
    {
        $duration1 = Duration::ofSeconds($s1, $m1);
        $duration2 = Duration::ofSeconds($s2, $m2);

        $expected = Duration::ofSeconds($expectedSeconds, $expectedMicros);
        $this->assertDurationEquals($expected, $duration1->plus($duration2));
    }

    /**
     * @return array
     */
    public function providerPlus()
    {
        return [
            [-1, -1, -1, -1, -2, -2],
            [-1, -1, -1, 0, -2, -1],
            [-1, -1, 0, -1, -1, -2],
            [-1, -1, 0, 0, -1, -1],
            [-1, -1, 0, 1, -1, 0],
            [-1, -1, 1, 0, 0, -1],
            [-1, -1, 1, 1, 0, 0],
            [-1, 0, -1, -1, -2, -1],
            [-1, 0, -1, 0, -2, 0],
            [-1, 0, 0, -1, -1, -1],
            [-1, 0, 0, 0, -1, 0],
            [-1, 0, 0, 1, 0, -999999],
            [-1, 0, 1, 0, 0, 0],
            [-1, 0, 1, 1, 0, 1],
            [0, -1, -1, -1, -1, -2],
            [0, -1, -1, 0, -1, -1],
            [0, -1, 0, -1, 0, -2],
            [0, -1, 0, 0, 0, -1],
            [0, -1, 0, 1, 0, 0],
            [0, -1, 1, 0, 0, 999999],
            [0, -1, 1, 1, 1, 0],
            [0, 0, -1, -1, -1, -1],
            [0, 0, -1, 0, -1, 0],
            [0, 0, 0, -1, 0, -1],
            [0, 0, 0, 0, 0, 0],
            [0, 0, 0, 1, 0, 1],
            [0, 0, 1, 0, 1, 0],
            [0, 0, 1, 1, 1, 1],
            [0, 1, -1, -1, -1, 0],
            [0, 1, -1, 0, 0, -999999],
            [0, 1, 0, -1, 0, 0],
            [0, 1, 0, 0, 0, 1],
            [0, 1, 0, 1, 0, 2],
            [0, 1, 1, 0, 1, 1],
            [0, 1, 1, 1, 1, 2],
            [1, 0, -1, -1, 0, -1],
            [1, 0, -1, 0, 0, 0],
            [1, 0, 0, -1, 0, 999999],
            [1, 0, 0, 0, 1, 0],
            [1, 0, 0, 1, 1, 1],
            [1, 0, 1, 0, 2, 0],
            [1, 0, 1, 1, 2, 1],
            [1, 1, -1, -1, 0, 0],
            [1, 1, -1, 0, 0, 1],
            [1, 1, 0, -1, 1, 0],
            [1, 1, 0, 0, 1, 1],
            [1, 1, 0, 1, 1, 2],
            [1, 1, 1, 0, 2, 1],
            [1, 1, 1, 1, 2, 2],

            [1, 999999, 1, 1, 3, 0],
            [1, 999999, -1, -1, 0, 999998],
            [-1, -999999, 1, 999998, 0, -1],
            [-1, -999999, -1, -1, -3, 0],
        ];
    }

    /**
     * @dataProvider providerPlusSeconds
     *
     * @param integer $seconds
     * @param integer $micros
     * @param integer $secondsToAdd
     * @param integer $expectedSeconds
     * @param integer $expectedMicros
     */
    public function testPlusSeconds($seconds, $micros, $secondsToAdd, $expectedSeconds, $expectedMicros)
    {
        $duration = Duration::ofSeconds($seconds, $micros)->plusSeconds($secondsToAdd);
        $expected = Duration::ofSeconds($expectedSeconds, $expectedMicros);

        $this->assertDurationEquals($expected, $duration);
    }

    /**
     * @return array
     */
    public function providerPlusSeconds()
    {
        return [
            [-1, 0, -1, -2, 0],
            [-1, 0,  0, -1, 0],
            [-1, 0,  1,  0, 0],
            [-1, 0,  2,  1, 0],

            [0, 0, -1, -1, 0],
            [0, 0,  0,  0, 0],
            [0, 0,  1,  1, 0],

            [1, 0, -2, -1, 0],
            [1, 0, -1,  0, 0],
            [1, 0,  0,  1, 0],
            [1, 0,  1,  2, 0],

            [~PHP_INT_MAX, 0, PHP_INT_MAX, -1, 0],
            [PHP_INT_MAX, 0, ~PHP_INT_MAX, -1, 0],
            [PHP_INT_MAX, 0, 0, PHP_INT_MAX, 0],

            [-1, -5,  2, 0,  999995],
            [ 1,  5, -2, 0, -999995],
        ];
    }

    /**
     * @dataProvider providerPlusMinutes
     *
     * @param integer $seconds
     * @param integer $minutesToAdd
     * @param integer $expectedSeconds
     */
    public function testPlusMinutes($seconds, $minutesToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusMinutes($minutesToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerPlusMinutes()
    {
        return [
            [-1, -1, -61],
            [-1, 0, -1],
            [-1, 1, 59],
            [-1, 2, 119],

            [0, -1, -60],
            [0, 0, 0],
            [0, 1, 60],

            [1, -2, -119],
            [1, -1, -59],
            [1, 0, 1],
            [1, 1, 61]
        ];
    }

    /**
     * @dataProvider providerPlusHours
     *
     * @param integer $seconds
     * @param integer $hoursToAdd
     * @param integer $expectedSeconds
     */
    public function testPlusHours($seconds, $hoursToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusHours($hoursToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerPlusHours()
    {
        return [
            [-1, -1, -3601],
            [-1, 0, -1],
            [-1, 1, 3599],
            [-1, 2, 7199],

            [0, -1, -3600],
            [0, 0, 0],
            [0, 1, 3600],

            [1, -2, -7199],
            [1, -1, -3599],
            [1, 0, 1],
            [1, 1, 3601]
        ];
    }

    /**
     * @dataProvider providerPlusDays
     *
     * @param integer $seconds
     * @param integer $daysToAdd
     * @param integer $expectedSeconds
     */
    public function testPlusDays($seconds, $daysToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusDays($daysToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerPlusDays()
    {
        return [
            [-1, -1, -86401],
            [-1, 0, -1],
            [-1, 1, 86399],
            [-1, 2, 172799],

            [0, -1, -86400],
            [0, 0, 0],
            [0, 1, 86400],

            [1, -2, -172799],
            [1, -1, -86399],
            [1, 0, 1],
            [1, 1, 86401]
        ];
    }

    /**
     * @dataProvider providerMinusSeconds
     *
     * @param integer $seconds
     * @param integer $secondsToSubtract
     * @param integer $expectedSeconds
     */
    public function testMinusSeconds($seconds, $secondsToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusSeconds($secondsToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerMinusSeconds()
    {
        return [
            [0, 0, 0],
            [0, 1, -1],
            [0, -1, 1],
            [0, PHP_INT_MAX, - PHP_INT_MAX],
            [0, ~PHP_INT_MAX + 1, PHP_INT_MAX],
            [1, 0, 1],
            [1, 1, 0],
            [1, -1, 2],
            [1, PHP_INT_MAX - 1, - PHP_INT_MAX + 2],
            [1, ~PHP_INT_MAX + 2, PHP_INT_MAX],
            [1, PHP_INT_MAX, - PHP_INT_MAX + 1],
            [-1, 0, -1],
            [-1, 1, -2],
            [-1, -1, 0],
            [-1, PHP_INT_MAX, ~PHP_INT_MAX],
            [-1, ~PHP_INT_MAX + 1, PHP_INT_MAX - 1]
        ];
    }

    /**
     * @dataProvider providerMinusMinutes
     *
     * @param integer $seconds
     * @param integer $minutesToSubtract
     * @param integer $expectedSeconds
     */
    public function testMinusMinutes($seconds, $minutesToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusMinutes($minutesToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerMinusMinutes()
    {
        return [
            [-1, -1, 59],
            [-1, 0, -1],
            [-1, 1, -61],
            [-1, 2, -121],

            [0, -1, 60],
            [0, 0, 0],
            [0, 1, -60],

            [1, -2, 121],
            [1, -1, 61],
            [1, 0, 1],
            [1, 1, -59]
        ];
    }

    /**
     * @dataProvider providerMinusHours
     *
     * @param integer $seconds
     * @param integer $hoursToSubtract
     * @param integer $expectedSeconds
     */
    public function testMinusHours($seconds, $hoursToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusHours($hoursToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerMinusHours()
    {
        return [
            [-1, -1, 3599],
            [-1, 0, -1],
            [-1, 1, -3601],
            [-1, 2, -7201],

            [0, -1, 3600],
            [0, 0, 0],
            [0, 1, -3600],

            [1, -2, 7201],
            [1, -1, 3601],
            [1, 0, 1],
            [1, 1, -3599]
        ];
    }

    /**
     * @dataProvider providerMinusDays
     *
     * @param integer $seconds
     * @param integer $daysToSubtract
     * @param integer $expectedSeconds
     */
    public function testMinusDays($seconds, $daysToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusDays($daysToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function providerMinusDays()
    {
        return [
            [-1, -1, 86399],
            [-1, 0, -1],
            [-1, 1, -86401],
            [-1, 2, -172801],

            [0, -1, 86400],
            [0, 0, 0],
            [0, 1, -86400],

            [1, -2, 172801],
            [1, -1, 86401],
            [1, 0, 1],
            [1, 1, -86399]
        ];
    }

    public function testMultipliedBy()
    {
        for ($seconds = -3; $seconds <= 3; $seconds++) {
            for ($multiplicand = -3; $multiplicand <= 3; $multiplicand++) {
                $duration = Duration::ofSeconds($seconds)->multipliedBy($multiplicand);
                $this->assertEquals($seconds * $multiplicand, $duration->getSeconds());
            }
        }
    }

    public function testMultipliedByMax()
    {
        $duration = Duration::ofSeconds(1)->multipliedBy(PHP_INT_MAX);
        $this->assertTrue($duration->isEqualTo(Duration::ofSeconds(PHP_INT_MAX)));
    }

    public function testMultipliedByMin()
    {
        $duration = Duration::ofSeconds(1)->multipliedBy(~ PHP_INT_MAX);
        $this->assertTrue($duration->isEqualTo(Duration::ofSeconds(~ PHP_INT_MAX)));
    }

    /**
     * @dataProvider providerDividedBy
     *
     * @param integer $seconds
     * @param integer $micros
     * @param integer $divisor
     * @param integer $expectedSeconds
     * @param integer $expectedMicros
     */
    public function testDividedBy($seconds, $micros, $divisor, $expectedSeconds, $expectedMicros)
    {
        $duration = Duration::ofSeconds($seconds, $micros);
        $expected = Duration::ofSeconds($expectedSeconds, $expectedMicros);

        $this->assertDurationEquals($expected, $duration->dividedBy($divisor));
        $this->assertDurationEquals($expected, $duration->negated()->dividedBy(-$divisor));
        $this->assertDurationEquals($expected->negated(), $duration->negated()->dividedBy($divisor));
        $this->assertDurationEquals($expected->negated(), $duration->dividedBy(-$divisor));
    }

    /**
     * @return array
     */
    public function providerDividedBy()
    {
        return [
            [3, 0, 1, 3, 0],
            [3, 0, 2, 1, 500000],
            [3, 0, 3, 1, 0],
            [3, 0, 4, 0, 750000],
            [3, 0, 5, 0, 600000],
            [3, 0, 6, 0, 500000],
            [3, 0, 7, 0, 428571],
            [3, 0, 8, 0, 375000],
            [0, 2, 2, 0, 1],
            [0, 1, 2, 0, 0]
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDividedByZeroThrowsException()
    {
        Duration::zero()->dividedBy(0);
    }

    /**
     * @dataProvider providerNegated
     *
     * @param integer $seconds         The duration in seconds.
     * @param integer $micros          The microseconds adjustement to the duration.
     * @param integer $expectedSeconds The expected seconds of the negated duration.
     * @param integer $expectedMicros  The expected microseconds adjustment of the negated duration.
     */
    public function testNegated($seconds, $micros, $expectedSeconds, $expectedMicros)
    {
        $duration = Duration::ofSeconds($seconds, $micros)->negated();

        $this->assertSame($expectedSeconds, $duration->getSeconds());
        $this->assertSame($expectedMicros, $duration->getMicroseconds());
    }

    /**
     * @return array
     */
    public function providerNegated()
    {
        return [
            [1, 0, -1, 0],
            [-1, 0, 1, 0],
            [1, 1, -2, 999999],
            [-2, 999999, 1, 1],
            [-1, 1, 0, 999999],
            [0, 999999, -1, 1]
        ];
    }

    public function testAbs()
    {
        for ($seconds = -3; $seconds <= 3; $seconds++) {
            $duration = Duration::ofSeconds($seconds)->abs();
            $this->assertEquals(abs($seconds), $duration->getSeconds());
        }
    }

    public function testComparisons()
    {
        $this->doTestComparisons([
            Duration::ofDays(-1),
            Duration::ofHours(-2),
            Duration::ofHours(-1),
            Duration::ofMinutes(-2),
            Duration::ofMinutes(-1),
            Duration::ofSeconds(-2),
            Duration::ofSeconds(-1),
            Duration::zero(),
            Duration::ofSeconds(1),
            Duration::ofSeconds(2),
            Duration::ofMinutes(1),
            Duration::ofMinutes(2),
            Duration::ofHours(1),
            Duration::ofHours(2),
            Duration::ofDays(1),
        ]);
    }

    /**
     * @param Duration[] $durations
     */
    private function doTestComparisons(array $durations)
    {
        for ($i = 0; $i < count($durations); $i++) {
            $a = $durations[$i];
            for ($j = 0; $j < count($durations); $j++) {
                $b = $durations[$j];
                if ($i < $j) {
                    $this->assertLessThan(0, $a->compareTo($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isLessThan($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isLessThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isGreaterThan($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isGreaterThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isEqualTo($b), $a . ' <=> ' . $b);
                }
                elseif ($i > $j) {
                    $this->assertGreaterThan(0, $a->compareTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isLessThan($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isLessThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isGreaterThan($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isGreaterThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isEqualTo($b), $a . ' <=> ' . $b);
                }
                else {
                    $this->assertEquals(0, $a->compareTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isLessThan($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isLessThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isGreaterThan($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isGreaterThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isEqualTo($b), $a . ' <=> ' . $b);
                }

                if ($i <= $j) {
                    $this->assertLessThanOrEqual(0, $a->compareTo($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isLessThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isGreaterThan($b), $a . ' <=> ' . $b);
                }
                if ($i >= $j) {
                    $this->assertGreaterThanOrEqual(0, $a->compareTo($b), $a . ' <=> ' . $b);
                    $this->assertTrue($a->isGreaterThanOrEqualTo($b), $a . ' <=> ' . $b);
                    $this->assertFalse($a->isLessThan($b), $a . ' <=> ' . $b);
                }
            }
        }
    }

    public function testEquals()
    {
        $test5a = Duration::ofSeconds(5);
        $test5b = Duration::ofSeconds(5);
        $test6a = Duration::ofSeconds(6);
        $test6b = Duration::ofSeconds(6);

        $this->assertTrue($test5a->isEqualTo($test5a));
        $this->assertTrue($test5a->isEqualTo($test5b));
        $this->assertFalse($test5a->isEqualTo($test6a));
        $this->assertFalse($test5a->isEqualTo($test6b));

        $this->assertTrue($test5b->isEqualTo($test5a));
        $this->assertTrue($test5b->isEqualTo($test5b));
        $this->assertFalse($test5b->isEqualTo($test6a));
        $this->assertFalse($test5b->isEqualTo($test6b));

        $this->assertFalse($test6a->isEqualTo($test5a));
        $this->assertFalse($test6a->isEqualTo($test5b));
        $this->assertTrue($test6a->isEqualTo($test6a));
        $this->assertTrue($test6a->isEqualTo($test6b));

        $this->assertFalse($test6b->isEqualTo($test5a));
        $this->assertFalse($test6b->isEqualTo($test5b));
        $this->assertTrue($test6b->isEqualTo($test6a));
        $this->assertTrue($test6b->isEqualTo($test6b));
    }

    /**
     * @dataProvider providerToString
     *
     * @param integer $seconds
     * @param integer $microseconds
     * @param string  $expected
     */
    public function testToString($seconds, $microseconds, $expected)
    {
        $this->assertEquals($expected, Duration::ofSeconds($seconds, $microseconds)->toString());
    }

    /**
     * @return array
     */
    public function providerToString()
    {
        return [
            [0, 0, 'PT0S'],
            [1, 0, 'PT1S'],
            [-1, 0, 'PT-1S'],
            [1, 1, 'PT1.000001S'],
            [-9, -999, 'PT-9.000999S']
        ];
    }
}
