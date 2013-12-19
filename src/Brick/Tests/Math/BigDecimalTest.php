<?php

namespace Brick\Tests\Math;

use Brick\Math\ArithmeticException;
use Brick\Math\Decimal;
use Brick\Math\RoundingMode;

/**
 * Unit test for class BigDecimal.
 */
class BigDecimalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider roundingModeProvider
     *
     * @param Decimal $number
     * @param Decimal $expected
     * @param integer $scale
     * @param integer $roundingMode
     */
    private function doTest(Decimal $number, Decimal $expected, $scale, $roundingMode)
    {
        $actual = $number->withScale($scale, $roundingMode);

        $this->assertTrue(
            $actual->isEqualTo($expected),
            sprintf('Expected %s, got %s', $expected->toString(), $actual->toString())
        );
    }

    /**
     * @dataProvider roundingModeProvider
     *
     * @param integer     $roundingMode The rounding mode.
     * @param string      $number       The number to round.
     * @param string|null $expected     The expected result, or null if an exception is expected.
     */
    public function testRoundingMode($roundingMode, $number, $expected)
    {
        $ten = Decimal::of(10);

        $number = Decimal::of($number);
        $numberDividedByTen = $number->dividedBy($ten, $number->getScale() + 1);

        if ($expected === null) {
            try {
                $number->withScale(0, $roundingMode);
                $numberDividedByTen->withScale(1, $roundingMode);
            } catch (ArithmeticException $e) {
                return;
            }

            $this->fail('Rounding %s did not trigger an ArithmeticException as expected.', $number->toString());
        }

        $expected = Decimal::of($expected);
        $expectedDividedByTen = $expected->dividedBy($ten, $expected->getScale() + 1);

        $this->doTest($number, $expected, 0, $roundingMode);
        $this->doTest($numberDividedByTen, $expectedDividedByTen, 1, $roundingMode);
    }

    /**
     * @return array
     */
    public function roundingModeProvider()
    {
        return [
            [RoundingMode::ROUND_UP,  '5.5',   '6'],
            [RoundingMode::ROUND_UP,  '2.5',   '3'],
            [RoundingMode::ROUND_UP,  '1.6',   '2'],
            [RoundingMode::ROUND_UP,  '1.1',   '2'],
            [RoundingMode::ROUND_UP,  '1.01',  '2'],
            [RoundingMode::ROUND_UP,  '1.0',   '1'],
            [RoundingMode::ROUND_UP, '-1.0',  '-1'],
            [RoundingMode::ROUND_UP, '-1.01', '-2'],
            [RoundingMode::ROUND_UP, '-1.1',  '-2'],
            [RoundingMode::ROUND_UP, '-1.6',  '-2'],
            [RoundingMode::ROUND_UP, '-2.5',  '-3'],
            [RoundingMode::ROUND_UP, '-5.5',  '-6'],

            [RoundingMode::ROUND_DOWN,  '5.5',   '5'],
            [RoundingMode::ROUND_DOWN,  '2.5',   '2'],
            [RoundingMode::ROUND_DOWN,  '1.6',   '1'],
            [RoundingMode::ROUND_DOWN,  '1.1',   '1'],
            [RoundingMode::ROUND_DOWN,  '1.01',  '1'],
            [RoundingMode::ROUND_DOWN,  '1.0',   '1'],
            [RoundingMode::ROUND_DOWN, '-1.0',  '-1'],
            [RoundingMode::ROUND_DOWN, '-1.01', '-1'],
            [RoundingMode::ROUND_DOWN, '-1.1',  '-1'],
            [RoundingMode::ROUND_DOWN, '-1.6',  '-1'],
            [RoundingMode::ROUND_DOWN, '-2.5',  '-2'],
            [RoundingMode::ROUND_DOWN, '-5.5',  '-5'],

            [RoundingMode::ROUND_CEILING,  '5.5',   '6'],
            [RoundingMode::ROUND_CEILING,  '2.5',   '3'],
            [RoundingMode::ROUND_CEILING,  '1.6',   '2'],
            [RoundingMode::ROUND_CEILING,  '1.1',   '2'],
            [RoundingMode::ROUND_CEILING,  '1.01',  '2'],
            [RoundingMode::ROUND_CEILING,  '1.0',   '1'],
            [RoundingMode::ROUND_CEILING, '-1.0',  '-1'],
            [RoundingMode::ROUND_CEILING, '-1.01', '-1'],
            [RoundingMode::ROUND_CEILING, '-1.1',  '-1'],
            [RoundingMode::ROUND_CEILING, '-1.6',  '-1'],
            [RoundingMode::ROUND_CEILING, '-2.5',  '-2'],
            [RoundingMode::ROUND_CEILING, '-5.5',  '-5'],

            [RoundingMode::ROUND_FLOOR,  '5.5',   '5'],
            [RoundingMode::ROUND_FLOOR,  '2.5',   '2'],
            [RoundingMode::ROUND_FLOOR,  '1.6',   '1'],
            [RoundingMode::ROUND_FLOOR,  '1.1',   '1'],
            [RoundingMode::ROUND_FLOOR,  '1.01',  '1'],
            [RoundingMode::ROUND_FLOOR,  '1.0',   '1'],
            [RoundingMode::ROUND_FLOOR, '-1.0',  '-1'],
            [RoundingMode::ROUND_FLOOR, '-1.01', '-2'],
            [RoundingMode::ROUND_FLOOR, '-1.1',  '-2'],
            [RoundingMode::ROUND_FLOOR, '-1.6',  '-2'],
            [RoundingMode::ROUND_FLOOR, '-2.5',  '-3'],
            [RoundingMode::ROUND_FLOOR, '-5.5',  '-6'],

            [RoundingMode::ROUND_HALF_UP,  '5.5',   '6'],
            [RoundingMode::ROUND_HALF_UP,  '2.5',   '3'],
            [RoundingMode::ROUND_HALF_UP,  '1.6',   '2'],
            [RoundingMode::ROUND_HALF_UP,  '1.1',   '1'],
            [RoundingMode::ROUND_HALF_UP,  '1.01',  '1'],
            [RoundingMode::ROUND_HALF_UP,  '1.0',   '1'],
            [RoundingMode::ROUND_HALF_UP, '-1.0',  '-1'],
            [RoundingMode::ROUND_HALF_UP, '-1.01', '-1'],
            [RoundingMode::ROUND_HALF_UP, '-1.1',  '-1'],
            [RoundingMode::ROUND_HALF_UP, '-1.6',  '-2'],
            [RoundingMode::ROUND_HALF_UP, '-2.5',  '-3'],
            [RoundingMode::ROUND_HALF_UP, '-5.5',  '-6'],

            [RoundingMode::ROUND_HALF_DOWN,  '5.5',   '5'],
            [RoundingMode::ROUND_HALF_DOWN,  '2.5',   '2'],
            [RoundingMode::ROUND_HALF_DOWN,  '1.6',   '2'],
            [RoundingMode::ROUND_HALF_DOWN,  '1.1',   '1'],
            [RoundingMode::ROUND_HALF_DOWN,  '1.01',  '1'],
            [RoundingMode::ROUND_HALF_DOWN,  '1.0',   '1'],
            [RoundingMode::ROUND_HALF_DOWN, '-1.0',  '-1'],
            [RoundingMode::ROUND_HALF_DOWN, '-1.01', '-1'],
            [RoundingMode::ROUND_HALF_DOWN, '-1.1',  '-1'],
            [RoundingMode::ROUND_HALF_DOWN, '-1.6',  '-2'],
            [RoundingMode::ROUND_HALF_DOWN, '-2.5',  '-2'],
            [RoundingMode::ROUND_HALF_DOWN, '-5.5',  '-5'],

            [RoundingMode::ROUND_HALF_EVEN,  '5.5',   '6'],
            [RoundingMode::ROUND_HALF_EVEN,  '2.5',   '2'],
            [RoundingMode::ROUND_HALF_EVEN,  '1.6',   '2'],
            [RoundingMode::ROUND_HALF_EVEN,  '1.1',   '1'],
            [RoundingMode::ROUND_HALF_EVEN,  '1.01',  '1'],
            [RoundingMode::ROUND_HALF_EVEN,  '1.0',   '1'],
            [RoundingMode::ROUND_HALF_EVEN, '-1.0',  '-1'],
            [RoundingMode::ROUND_HALF_EVEN, '-1.01', '-1'],
            [RoundingMode::ROUND_HALF_EVEN, '-1.1',  '-1'],
            [RoundingMode::ROUND_HALF_EVEN, '-1.6',  '-2'],
            [RoundingMode::ROUND_HALF_EVEN, '-2.5',  '-2'],
            [RoundingMode::ROUND_HALF_EVEN, '-5.5',  '-6'],

            [RoundingMode::ROUND_UNNECESSARY,  '5.5',  null],
            [RoundingMode::ROUND_UNNECESSARY,  '2.5',  null],
            [RoundingMode::ROUND_UNNECESSARY,  '1.6',  null],
            [RoundingMode::ROUND_UNNECESSARY,  '1.1',  null],
            [RoundingMode::ROUND_UNNECESSARY,  '1.01', null],
            [RoundingMode::ROUND_UNNECESSARY,  '1.0',  '1'],
            [RoundingMode::ROUND_UNNECESSARY, '-1.0', '-1'],
            [RoundingMode::ROUND_UNNECESSARY, '-1.01', null],
            [RoundingMode::ROUND_UNNECESSARY, '-1.1',  null],
            [RoundingMode::ROUND_UNNECESSARY, '-1.6',  null],
            [RoundingMode::ROUND_UNNECESSARY, '-2.5',  null],
            [RoundingMode::ROUND_UNNECESSARY, '-5.5',  null]
        ];
    }
}
