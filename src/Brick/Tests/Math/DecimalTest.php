<?php

namespace Brick\Tests\Math;

use Brick\Math\Calculator;
use Brick\Math\Decimal;
use Brick\Math\RoundingMode;

/**
 * Unit tests for class Decimal.
 */
class DecimalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Decimal|string $expected
     * @param Decimal        $actual
     */
    private function assertDecimalEquals($expected, Decimal $actual)
    {
        $this->assertTrue(
            $actual->isEqualTo($expected),
            sprintf('Expected %s, got %s', $expected, $actual)
        );
    }

    public function testEquality()
    {
        $a = Decimal::of('1.0');
        $b = Decimal::of('1.00');

        $this->assertDecimalEquals($a, $b);
    }

    public function testDecimal()
    {
        $number1 = Decimal::of('1.0123456789');
        $number2 = Decimal::of('2.0987654321');

        $expectedSum = Decimal::of('3.111111111');
        $expectedDifference = Decimal::of('-1.0864197532');
        $expectedProduct = Decimal::of('2.12467611621112635269');

        // Check addition / subtraction
        $this->assertDecimalEquals($expectedSum, $number1->plus($number2));
        $this->assertDecimalEquals($expectedDifference, $number1->minus($number2));
        $this->assertDecimalEquals($number1, $number1->plus($number2)->minus($number2));

        // Check multiplication
        $this->assertDecimalEquals($expectedProduct, $number1->multipliedBy($number2));

        $times3 = $number1->multipliedBy(Decimal::of(3));
        $this->assertDecimalEquals($times3, $number1->plus($number1)->plus($number1));

        // Check negation
        $this->assertTrue($number1->negated()->negated()->isEqualTo($number1));
        $this->assertTrue($number1->negated()->isNegative());

        // Check absolute value
        $this->assertTrue($number1->negated()->abs()->isEqualTo($number1));
        $this->assertTrue($number2->negated()->abs()->isEqualTo($number2->abs()));

        // Check positive test
        $this->assertTrue($number1->isPositive());
        $this->assertFalse($number1->minus($number1)->isPositive());
        $this->assertTrue($number1->minus($number1)->isPositiveOrZero());
        $this->assertFalse($number1->negated()->isPositive());
        $this->assertTrue($number1->negated()->isNegative());

        $this->assertTrue($number1->isLessThan($number2));
        $this->assertTrue($number1->isLessThanOrEqualTo($number2));
        $this->assertFalse($number1->isGreaterThan($number2));
        $this->assertFalse($number1->isGreaterThanOrEqualTo($number2));

        $this->assertTrue($number2->isGreaterThan($number1));
        $this->assertTrue($number2->isGreaterThanOrEqualTo($number1));
        $this->assertFalse($number2->isLessThan($number1));
        $this->assertFalse($number2->isLessThanOrEqualTo($number1));

        $this->assertFalse($number1->isLessThan($number1));
        $this->assertTrue($number1->isLessThanOrEqualTo($number1));
        $this->assertFalse($number1->isGreaterThan($number1));
        $this->assertTrue($number1->isGreaterThanOrEqualTo($number1));
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testDivisionByZero()
    {
        Decimal::one()->dividedBy(Decimal::zero());
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testDivisionWithRoundingNecessary()
    {
        $p = Decimal::of('1.234');
        $q = Decimal::of('123.456');

        $p->dividedBy($q);
    }

    public function testDivisionWithRounding()
    {
        $p = Decimal::of('1.234');
        $q = Decimal::of('123.456');
        $r = Decimal::of('0.00999546397096941420425090720580611715914981855883');

        $this->assertTrue($p->dividedBy($q, 50, RoundingMode::DOWN)->isEqualTo($r));
    }

    public function testDivisionWithNoRoundingNecessary()
    {
        $p = Decimal::of('0.123456789');
        $q = Decimal::of('0.00244140625');
        $r = Decimal::of('50.5679007744');

        $this->assertDecimalEquals($r, $p->dividedBy($q, 10));
    }

    /**
     * @dataProvider divisionOfNegativeNumbersProvider
     */
    public function testDivisionOfNegativeNumbers($a, $b, $expected)
    {
        $actual = Decimal::of($a)->dividedBy($b);

        $this->assertDecimalEquals($expected, $actual);
    }

    /**
     * @return array
     */
    public function divisionOfNegativeNumbersProvider()
    {
        return [
            [ '21',  '7',  '3'],
            [ '21', '-7', '-3'],
            ['-21',  '7', '-3'],
            ['-21', '-7',  '3']
        ];
    }

    /**
     * @return array
     */
    public function modProvider()
    {
        return [
            [ '1.25',  '0.25',  '0.00'],
            [ '1.26',  '0.25',  '0.01'],
            [ '1.26', '-0.25',  '0.01'],
            ['-1.26',  '0.25', '-0.01'],
            ['-1.26', '-0.25', '-0.01'],

            [ '1.251',  '0.25',  '0.001'],
            [ '1.261',  '0.25',  '0.011'],
            [ '1.261', '-0.25',  '0.011'],
            ['-1.261',  '0.25', '-0.011'],
            ['-1.261', '-0.25', '-0.011'],

            [ '1.25',  '0.251',  '0.246'],
            [ '1.26',  '0.251',  '0.005'],
            [ '1.26', '-0.251',  '0.005'],
            ['-1.26',  '0.251', '-0.005'],
            ['-1.26', '-0.251', '-0.005'],

            [ '1',  '0.03' , '0.01'],
            [ '1', '-0.03' , '0.01'],
            ['-1',  '0.03', '-0.01'],
            ['-1', '-0.03', '-0.01'],

            [ '1.0',  '0.03',  '0.01'],
            [ '1.0', '-0.03',  '0.01'],
            ['-1.0',  '0.03', '-0.01'],
            ['-1.0', '-0.03', '-0.01'],

            [ '1.234',  '1',  '0.234'],
            [ '1.234', '-1',  '0.234'],
            ['-1.234',  '1', '-0.234'],
            ['-1.234', '-1', '-0.234'],

            [ '1.234',  '1.0',  '0.234'],
            [ '1.234', '-1.0',  '0.234'],
            ['-1.234',  '1.0', '-0.234'],
            ['-1.234', '-1.0', '-0.234']
        ];
    }

    /**
     * @dataProvider importExportProvider
     */
    public function testImportExport($value, $unscaledValue, $scale)
    {
        $decimal = Decimal::of($value);

        $this->assertSame($unscaledValue, $decimal->getUnscaledValue());
        $this->assertSame($scale, $decimal->getScale());
        $this->assertSame($value, $decimal->toString());
    }

    /**
     * @return array
     */
    public function importExportProvider()
    {
        return [
            ['0',    '0',   0],
            ['0.0',  '0',   1],
            ['0.1',  '1',   1],
            ['0.00', '0',   2],
            ['0.01', '1',   2],
            ['0.10', '10',  2],
            ['0.11', '11',  2],
            ['1',    '1',   0],
            ['1.0',  '10',  1],
            ['1.1',  '11',  1],
            ['1.00', '100', 2],
            ['1.01', '101', 2],
            ['1.10', '110', 2],
            ['1.11', '111', 2]
        ];
    }

    /**
     * @dataProvider isZeroProvider
     */
    public function testIsZero($number)
    {
        $this->assertTrue(Decimal::of($number)->isZero());
    }

    /**
     * @return array
     */
    public function isZeroProvider()
    {
        return [
            ['0'],
            ['0.0'],
            ['0.00'],
            ['-0.00'],
            ['-0.0'],
            ['-0']
        ];
    }
}
