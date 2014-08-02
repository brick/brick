<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Instant;

/**
 * Unit tests for class Instant.
 */
class InstantTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerOf
     *
     * @param integer $seconds              The duration in seconds.
     * @param integer $microAdjustment      The microseconds adjustement to the duration.
     * @param integer $expectedSeconds      The expected adjusted duration seconds.
     * @param integer $expectedMicroseconds The expected adjusted duration microseconds.
     */
    public function testOf($seconds, $microAdjustment, $expectedSeconds, $expectedMicroseconds)
    {
        $duration = Instant::of($seconds, $microAdjustment);

        $this->assertSame($expectedSeconds, $duration->getTimestamp());
        $this->assertSame($expectedMicroseconds, $duration->getMicroseconds());
    }

    /**
     * @return array
     */
    public function providerOf()
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
}
