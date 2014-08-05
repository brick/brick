<?php

namespace Brick\Tests\DateTime;

use Brick\DateTime\Instant;

/**
 * Unit tests for class Instant.
 */
class InstantTest extends AbstractTestCase
{
    /**
     * @dataProvider providerOf
     *
     * @param integer $seconds         The duration in seconds.
     * @param integer $nanoAdjustment  The nanoseconds adjustement to the duration.
     * @param integer $expectedSeconds The expected adjusted duration seconds.
     * @param integer $expectedNanos   The expected adjusted duration nanoseconds.
     */
    public function testOf($seconds, $nanoAdjustment, $expectedSeconds, $expectedNanos)
    {
        $duration = Instant::of($seconds, $nanoAdjustment);

        $this->assertSame($expectedSeconds, $duration->getTimestamp());
        $this->assertSame($expectedNanos, $duration->getNanos());
    }

    /**
     * @return array
     */
    public function providerOf()
    {
        return [
            [3, 1, 3, 1],
            [4, -999999999, 3, 1],
            [2, 1000000001, 3, 1],
            [-3, 1, -3, 1],
            [-4, 1000000001, -3, 1],
            [-2, -999999999, -3, 1],
            [1, -1000000001, -1, 999999999],
            [-1, -1000000001, -3, 999999999]
        ];
    }
}
