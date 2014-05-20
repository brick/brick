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
    public function testEquality()
    {
        $this->assertEquals('1', '1');
        $this->assertEquals('1', '1.0');
        $this->assertEquals('1', '1.00');
    }

    public function testDecimal()
    {
        $number1 = Decimal::of('1.0123456789');
        $number2 = Decimal::of('2.0987654321');

        // Test addition / subtraction
        $this->assertEquals('3.111111111', $number1->plus($number2));
        $this->assertEquals('-1.0864197532', $number1->minus($number2));
        $this->assertEquals($number1, $number1->plus($number2)->minus($number2));

        // Test multiplication
        $this->assertEquals('2.12467611621112635269', $number1->multipliedBy($number2));

        $times3 = $number1->multipliedBy(3);
        $this->assertEquals($times3, $number1->plus($number1)->plus($number1));

        // Test negation
        $this->assertTrue($number1->negated()->negated()->isEqualTo($number1));
        $this->assertTrue($number1->negated()->isNegative());

        // Test absolute value
        $this->assertTrue($number1->negated()->abs()->isEqualTo($number1));
        $this->assertTrue($number2->negated()->abs()->isEqualTo($number2->abs()));

        // Test sign
        $this->assertTrue($number1->isPositive());
        $this->assertFalse($number1->minus($number1)->isPositive());
        $this->assertTrue($number1->minus($number1)->isPositiveOrZero());
        $this->assertFalse($number1->negated()->isPositive());
        $this->assertTrue($number1->negated()->isNegative());

        // Test comparison
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
        Decimal::of(1)->dividedBy(0);
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testDivisionWithRoundingNecessary()
    {
        $p = Decimal::of('1.234')->dividedBy('123.456');
    }

    public function testDivisionWithRounding()
    {
        $p = Decimal::of('1.234');
        $q = Decimal::of('123.456');
        $r = Decimal::of('0.00999546397096941420425090720580611715914981855883');

        $this->assertEquals($r, $p->dividedBy($q, 50, RoundingMode::DOWN));
    }

    public function testDivisionWithNoRoundingNecessary()
    {
        $p = Decimal::of('0.123456789');
        $q = Decimal::of('0.00244140625');
        $r = Decimal::of('50.5679007744');

        $this->assertEquals($r, $p->dividedBy($q, 10));
    }

    /**
     * @dataProvider divisionOfNegativeNumbersProvider
     */
    public function testDivisionOfNegativeNumbers($a, $b, $expected)
    {
        $this->assertEquals($expected, Decimal::of($a)->dividedBy($b));
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
            ['0',      '0',   0],
            ['0.0',    '0',   1],
            ['0.1',    '1',   1],
            ['0.00',   '0',   2],
            ['0.01',   '1',   2],
            ['0.10',   '10',  2],
            ['0.11',   '11',  2],
            ['1',      '1',   0],
            ['1.0',    '10',  1],
            ['1.1',    '11',  1],
            ['1.00',   '100', 2],
            ['1.01',   '101', 2],
            ['1.10',   '110', 2],
            ['1.11',   '111', 2],
            ['-0.1',  '-1',   1],
            ['-0.01', '-1',   2],
            ['-0.10', '-10',  2],
            ['-0.11', '-11',  2],
            ['-1',    '-1',   0],
            ['-1.0',  '-10',  1],
            ['-1.1',  '-11',  1],
            ['-1.00', '-100', 2],
            ['-1.01', '-101', 2],
            ['-1.10', '-110', 2],
            ['-1.11', '-111', 2]
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
