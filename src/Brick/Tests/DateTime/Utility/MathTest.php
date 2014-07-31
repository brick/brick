<?php

namespace Brick\Tests\DateTime\Utility;

use Brick\DateTime\Utility\Math;

/**
 * Unit tests for the Math utility class.
 */
class MathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerDiv
     *
     * @param integer $a The dividend.
     * @param integer $b The divisor.
     * @param integer $q The expected quotient.
     * @param integer $r The expected remainder.
     */
    public function testDiv($a, $b, $q, $r)
    {
        $quotient = Math::div($a, $b, $remainder);

        $this->assertSame($q, $quotient);
        $this->assertSame($r, $remainder);
    }

    /**
     * @return array
     */
    public function providerDiv()
    {
        return [
            [ 11,  5,  2,  1],
            [-11,  5, -2, -1],
            [ 11, -5, -2,  1],
            [-11, -5,  2, -1],
        ];
    }
}
