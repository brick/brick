<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Duration;
use Brick\DateTime\Instant;

/**
 * Unit tests for class Duration.
 */
class DurationTest extends \PHPUnit_Framework_TestCase
{
    public function testZero()
    {
        $this->assertEquals(0, Duration::zero()->getSeconds());
    }

    public function testFactorySeconds()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i, Duration::ofSeconds($i)->getSeconds());
        }
    }

    public function testFactoryMinutes()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i * 60, Duration::ofMinutes($i)->getSeconds());
        }
    }

    public function testFactoryHours()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i * 3600, Duration::ofHours($i)->getSeconds());
        }
    }

    public function testFactoryDays()
    {
        for ($i = -2; $i <= 2; $i++) {
            $this->assertEquals($i * 86400, Duration::ofDays($i)->getSeconds());
        }
    }

    /**
     * @dataProvider factoryBetweenInstantsProvider
     *
     * @param int $start
     * @param int $end
     * @param int $expectedSeconds
     */
    public function testFactoryBetweenInstants($start, $end, $expectedSeconds)
    {
        $duration = Duration::between(Instant::of($start), Instant::of($end));
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function factoryBetweenInstantsProvider()
    {
        return [
            [0, 0, 0],
            [3, 7, 4],
            [7, 3, -4]
        ];
    }

    /**
     * @dataProvider factoryParseProvider
     *
     * @param string $text
     * @param int    $expectedSeconds
     */
    public function testFactoryParse($text, $expectedSeconds)
    {
        $this->assertEquals($expectedSeconds, Duration::parse($text)->getSeconds());
    }

    /**
     * @return array
     */
    public function factoryParseProvider()
    {
        return [
            ['PT0S', 0],
            ['pT0S', 0],
            ['Pt0S', 0],
            ['PT0s', 0],

            ['PT1S', 1],
            ['PT12S', 12],
            ['PT123456789S', 123456789],
            ['PT' . PHP_INT_MAX . 'S', PHP_INT_MAX],

            ['PT-1S', -1],
            ['PT-12S', -12],
            ['PT-123456789S', -123456789],
            ['PT' . ~ PHP_INT_MAX . 'S', ~ PHP_INT_MAX]
        ];
    }

    /**
     * @dataProvider factoryParseFailuresProvider
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     *
     * @param string $text
     */
    public function testFactoryParseFailures($text)
    {
        Duration::parse($text);
    }

    /**
     * @return array
     */
    public function factoryParseFailuresProvider()
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
            ['PT-0S'],
            ['PT-00S'],
            ['PT-000S'],
            ['PT+1S'],
            ['PT-.S'],
            ['PT+.S'],

            ['PT1X2S'],
            ['PT0.1S'],
            ['PT1.S'],
            ['PT.1S']
        ];
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     */
    public function testFactoryParseTooBig()
    {
        Duration::parse('PT' . PHP_INT_MAX . '1S');
    }

    /**
     * @expectedException \Brick\DateTime\Parser\DateTimeParseException
     */
    public function testFactoryParseTooSmall()
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

    public function testIsPositiveOrZero()
    {
        $this->assertFalse(Duration::ofSeconds(-1)->isPositiveOrZero());
        $this->assertTrue(Duration::ofSeconds(0)->isPositiveOrZero());
        $this->assertTrue(Duration::ofSeconds(1)->isPositiveOrZero());
    }

    public function testIsNegative()
    {
        $this->assertTrue(Duration::ofSeconds(-1)->isNegative());
        $this->assertFalse(Duration::ofSeconds(0)->isNegative());
        $this->assertFalse(Duration::ofSeconds(1)->isNegative());
    }

    public function testIsNegativeOrZero()
    {
        $this->assertTrue(Duration::ofSeconds(-1)->isNegativeOrZero());
        $this->assertTrue(Duration::ofSeconds(0)->isNegativeOrZero());
        $this->assertFalse(Duration::ofSeconds(1)->isNegativeOrZero());
    }

    /**
     * @dataProvider plusSecondsProvider
     *
     * @param int $seconds
     * @param int $secondsToAdd
     * @param int $expectedSeconds
     */
    public function testPlusSeconds($seconds, $secondsToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusSeconds($secondsToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function plusSecondsProvider()
    {
        return [
            [-1, -1, -2],
            [-1, 0, -1],
            [-1, 1, 0],
            [-1, 2, 1],

            [0, -1, -1],
            [0, 0, 0],
            [0, 1, 1],

            [1, -2, -1],
            [1, -1, 0],
            [1, 0, 1],
            [1, 1, 2],

            [~PHP_INT_MAX, PHP_INT_MAX, -1],
            [PHP_INT_MAX, ~PHP_INT_MAX, -1],
            [PHP_INT_MAX, 0, PHP_INT_MAX]
        ];
    }

    /**
     * @dataProvider plusMinutesProvider
     *
     * @param int $seconds
     * @param int $minutesToAdd
     * @param int $expectedSeconds
     */
    public function testPlusMinutes($seconds, $minutesToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusMinutes($minutesToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function plusMinutesProvider()
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
     * @dataProvider plusHoursProvider
     *
     * @param int $seconds
     * @param int $hoursToAdd
     * @param int $expectedSeconds
     */
    public function testPlusHours($seconds, $hoursToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusHours($hoursToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function plusHoursProvider()
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
     * @dataProvider plusDaysProvider
     *
     * @param int $seconds
     * @param int $daysToAdd
     * @param int $expectedSeconds
     */
    public function testPlusDays($seconds, $daysToAdd, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->plusDays($daysToAdd);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function plusDaysProvider()
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
     * @dataProvider minusSecondsProvider
     *
     * @param int $seconds
     * @param int $secondsToSubtract
     * @param int $expectedSeconds
     */
    public function testMinusSeconds($seconds, $secondsToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusSeconds($secondsToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function minusSecondsProvider()
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
     * @dataProvider minusMinutesProvider
     *
     * @param int $seconds
     * @param int $minutesToSubtract
     * @param int $expectedSeconds
     */
    public function testMinusMinutes($seconds, $minutesToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusMinutes($minutesToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function minusMinutesProvider()
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
     * @dataProvider minusHoursProvider
     *
     * @param int $seconds
     * @param int $hoursToSubtract
     * @param int $expectedSeconds
     */
    public function testMinusHours($seconds, $hoursToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusHours($hoursToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function minusHoursProvider()
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
     * @dataProvider minusDaysProvider
     *
     * @param int $seconds
     * @param int $daysToSubtract
     * @param int $expectedSeconds
     */
    public function testMinusDays($seconds, $daysToSubtract, $expectedSeconds)
    {
        $duration = Duration::ofSeconds($seconds)->minusDays($daysToSubtract);
        $this->assertEquals($expectedSeconds, $duration->getSeconds());
    }

    /**
     * @return array
     */
    public function minusDaysProvider()
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

    public function testNegated()
    {
        for ($seconds = -3; $seconds <= 3; $seconds++) {
            $duration = Duration::ofSeconds($seconds)->negated();
            $this->assertEquals(- $seconds, $duration->getSeconds());
        }
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
     * @dataProvider toStringProvider
     *
     * @param int    $seconds
     * @param string $expected
     */
    public function testToString($seconds, $expected)
    {
        $this->assertEquals($expected, Duration::ofSeconds($seconds)->toString());
    }

    /**
     * @return array
     */
    public function toStringProvider()
    {
        return [
            [0, 'PT0S'],
            [1, 'PT1S'],
            [-1, 'PT-1S'],
        ];
    }
}
