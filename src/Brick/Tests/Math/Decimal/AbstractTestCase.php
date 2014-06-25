<?php

namespace Brick\Tests\Math\Decimal;

use Brick\Math\ArithmeticException;
use Brick\Math\Calculator;
use Brick\Math\Decimal;
use Brick\Math\RoundingMode;

/**
 * Unit tests for class Decimal.
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Brick\Math\Calculator
     */
    abstract public function getCalculator();

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        Calculator::set($this->getCalculator());
    }

    /**
     * @param Decimal|string $expected
     * @param Decimal|string $actual
     */
    private function assertDecimalEquals($expected, $actual)
    {
        $message = sprintf('Expected %s, got %s', $expected, $actual);
        $this->assertTrue(Decimal::of($actual)->isEqualTo($expected), $message);
    }

    public function testEquality()
    {
        $this->assertDecimalEquals('1', '1');
        $this->assertDecimalEquals('1', '1.0');
        $this->assertDecimalEquals('1', '1.00');
    }

    public function testDecimal()
    {
        $number1 = Decimal::of('1.0123456789');
        $number2 = Decimal::of('2.0987654321');

        // Test addition / subtraction
        $this->assertDecimalEquals('3.111111111', $number1->plus($number2));
        $this->assertDecimalEquals('-1.0864197532', $number1->minus($number2));
        $this->assertDecimalEquals($number1, $number1->plus($number2)->minus($number2));

        // Test multiplication
        $this->assertDecimalEquals('2.12467611621112635269', $number1->multipliedBy($number2));

        $times3 = $number1->multipliedBy(3);
        $this->assertDecimalEquals($times3, $number1->plus($number1)->plus($number1));

        // Test negation
        $this->assertDecimalEquals($number1, $number1->negated()->negated());
        $this->assertTrue($number1->negated()->isNegative());

        // Test absolute value
        $this->assertDecimalEquals($number1, $number1->negated()->abs());
        $this->assertDecimalEquals($number2->abs(), $number2->negated()->abs());

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
     * @dataProvider leadingPlusSignAndZeroProvider
     */
    public function testLeadingPlusSignAndZero($value, $expected)
    {
        $this->assertDecimalEquals($expected, $value);
    }

    /**
     * @return array
     */
    public function leadingPlusSignAndZeroProvider()
    {
        return [
            ['+9', '9'],
            ['+9.9', '9.9'],

            ['09', '9'],
            ['09.9', '9.9'],

            ['+09', '9'],
            ['+09.9', '9.9'],

            ['+9.9e1', '99'],
            ['+09.9e1', '99'],
        ];
    }

    /**
     * @dataProvider exponentProvider
     */
    public function testExponent($value, $expected)
    {
        $this->assertDecimalEquals($expected, $value);
        $this->assertDecimalEquals($expected, strtoupper($value));

        $this->assertDecimalEquals($expected, '+' . $value);
        $this->assertDecimalEquals($expected, '+' . strtoupper($value));

        $this->assertDecimalEquals('-' . $expected, '-' . $value);
        $this->assertDecimalEquals('-' . $expected, '-' . strtoupper($value));

        if (strpos($value, 'e+') !== false) {
            $value = str_replace('e+', 'e', $value);

            $this->assertDecimalEquals($expected, $value);
            $this->assertDecimalEquals($expected, strtoupper($value));
        }
    }

    /**
     * @return array
     */
    public function exponentProvider()
    {
        return [
            ['1e-2', '0.01'],
            ['1e-1', '0.1'],
            ['1e-0', '1'],
            ['1e+0', '1'],
            ['1e+1', '10'],
            ['1e+2', '100'],

            ['1.2e-2', '0.012'],
            ['1.2e-1', '0.12'],
            ['1.2e-0', '1.2'],
            ['1.2e+0', '1.2'],
            ['1.2e+1', '12'],
            ['1.2e+2', '120'],

            ['1.23e-2', '0.0123'],
            ['1.23e-1', '0.123'],
            ['1.23e-0', '1.23'],
            ['1.23e+0', '1.23'],
            ['1.23e+1', '12.3'],
            ['1.23e+2', '123'],

            ['0.1e-2', '0.001'],
            ['0.1e-1', '0.01'],
            ['0.1e-0', '0.1'],
            ['0.1e+0', '0.1'],
            ['0.1e+1', '1'],
            ['0.1e+2', '10'],

            ['0e-1', '0'],
            ['0e0', '0'],
            ['0e1', '0'],

            ['123.456e-4', '0.0123456'],
            ['123.456e-3', '0.123456'],
            ['123.456e-2', '1.23456'],
            ['123.456e-1', '12.3456'],
            ['123.456e-0', '123.456'],
            ['123.456e+0', '123.456'],
            ['123.456e+1', '1234.56'],
            ['123.456e+2', '12345.6'],
            ['123.456e+3', '123456'],
            ['123.456e+4', '1234560'],
        ];
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
        Decimal::of('1.234')->dividedBy('123.456');
    }

    public function testDivisionWithRounding()
    {
        $p = Decimal::of('1.234');
        $q = Decimal::of('123.456');
        $r = Decimal::of('0.00999546397096941420425090720580611715914981855883');

        $this->assertDecimalEquals($r, $p->dividedBy($q, 50, RoundingMode::DOWN));
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
        $this->assertDecimalEquals($expected, Decimal::of($a)->dividedBy($b));
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
            ['-1.11', '-111', 2],
        ];
    }

    /**
     * @dataProvider isZeroProvider
     */
    public function testIsZero($number)
    {
        $number = Decimal::of($number);

        $this->assertTrue($number->isZero());
        $this->assertSame('0', $number->getUnscaledValue());
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

    /**
     * @dataProvider roundingModeProvider
     *
     * @param integer     $roundingMode The rounding mode.
     * @param string      $number       The number to round.
     * @param string|null $two          The expected rounding to a scale of two, or null if an exception is expected.
     * @param string|null $one          The expected rounding to a scale of one, or null if an exception is expected.
     * @param string|null $zero         The expected rounding to a scale of zero, or null if an exception is expected.
     */
    public function testRoundingMode($roundingMode, $number, $two, $one, $zero)
    {
        $number = Decimal::of($number);

        $this->doTestRoundingMode($roundingMode, $number, '1', $two, $one, $zero);
        $this->doTestRoundingMode($roundingMode, $number->negated(), '-1', $two, $one, $zero);
    }

    /**
     * @param integer     $roundingMode The rounding mode.
     * @param Decimal     $number       The number to round.
     * @param string      $divisor      The divisor.
     * @param string|null $two          The expected rounding to a scale of two, or null if an exception is expected.
     * @param string|null $one          The expected rounding to a scale of one, or null if an exception is expected.
     * @param string|null $zero         The expected rounding to a scale of zero, or null if an exception is expected.
     */
    private function doTestRoundingMode($roundingMode, Decimal $number, $divisor, $two, $one, $zero)
    {
        foreach ([$zero, $one, $two] as $scale => $expected) {
            if ($expected === null) {
                try {
                    $number->dividedBy($divisor, $scale, $roundingMode);
                }
                catch (ArithmeticException $e) {
                    continue;
                }

                $this->fail('Rounding %s did not trigger an ArithmeticException as expected.', $number->toString());
            } else {
                $actual = $number->dividedBy($divisor, $scale, $roundingMode);
                $this->assertDecimalEquals($expected, $actual);
            }
        }
    }

    /**
     * @return array
     */
    public function roundingModeProvider()
    {
        return [
            [RoundingMode::UP,  '3.501',  '3.51',  '3.6',  '4'],
            [RoundingMode::UP,  '3.500',  '3.50',  '3.5',  '4'],
            [RoundingMode::UP,  '3.499',  '3.50',  '3.5',  '4'],
            [RoundingMode::UP,  '3.001',  '3.01',  '3.1',  '4'],
            [RoundingMode::UP,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::UP,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::UP,  '2.501',  '2.51',  '2.6',  '3'],
            [RoundingMode::UP,  '2.500',  '2.50',  '2.5',  '3'],
            [RoundingMode::UP,  '2.499',  '2.50',  '2.5',  '3'],
            [RoundingMode::UP,  '2.001',  '2.01',  '2.1',  '3'],
            [RoundingMode::UP,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::UP,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::UP,  '1.501',  '1.51',  '1.6',  '2'],
            [RoundingMode::UP,  '1.500',  '1.50',  '1.5',  '2'],
            [RoundingMode::UP,  '1.499',  '1.50',  '1.5',  '2'],
            [RoundingMode::UP,  '1.001',  '1.01',  '1.1',  '2'],
            [RoundingMode::UP,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::UP,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::UP,  '0.501',  '0.51',  '0.6',  '1'],
            [RoundingMode::UP,  '0.500',  '0.50',  '0.5',  '1'],
            [RoundingMode::UP,  '0.499',  '0.50',  '0.5',  '1'],
            [RoundingMode::UP,  '0.001',  '0.01',  '0.1',  '1'],
            [RoundingMode::UP,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::UP, '-0.001', '-0.01', '-0.1', '-1'],
            [RoundingMode::UP, '-0.499', '-0.50', '-0.5', '-1'],
            [RoundingMode::UP, '-0.500', '-0.50', '-0.5', '-1'],
            [RoundingMode::UP, '-0.501', '-0.51', '-0.6', '-1'],
            [RoundingMode::UP, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::UP, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::UP, '-1.001', '-1.01', '-1.1', '-2'],
            [RoundingMode::UP, '-1.499', '-1.50', '-1.5', '-2'],
            [RoundingMode::UP, '-1.500', '-1.50', '-1.5', '-2'],
            [RoundingMode::UP, '-1.501', '-1.51', '-1.6', '-2'],
            [RoundingMode::UP, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::UP, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::UP, '-2.001', '-2.01', '-2.1', '-3'],
            [RoundingMode::UP, '-2.499', '-2.50', '-2.5', '-3'],
            [RoundingMode::UP, '-2.500', '-2.50', '-2.5', '-3'],
            [RoundingMode::UP, '-2.501', '-2.51', '-2.6', '-3'],
            [RoundingMode::UP, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::UP, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::UP, '-3.001', '-3.01', '-3.1', '-4'],
            [RoundingMode::UP, '-3.499', '-3.50', '-3.5', '-4'],
            [RoundingMode::UP, '-3.500', '-3.50', '-3.5', '-4'],
            [RoundingMode::UP, '-3.501', '-3.51', '-3.6', '-4'],

            [RoundingMode::DOWN,  '3.501',  '3.50',  '3.5',  '3'],
            [RoundingMode::DOWN,  '3.500',  '3.50',  '3.5',  '3'],
            [RoundingMode::DOWN,  '3.499',  '3.49',  '3.4',  '3'],
            [RoundingMode::DOWN,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::DOWN,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::DOWN,  '2.999',  '2.99',  '2.9',  '2'],
            [RoundingMode::DOWN,  '2.501',  '2.50',  '2.5',  '2'],
            [RoundingMode::DOWN,  '2.500',  '2.50',  '2.5',  '2'],
            [RoundingMode::DOWN,  '2.499',  '2.49',  '2.4',  '2'],
            [RoundingMode::DOWN,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::DOWN,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::DOWN,  '1.999',  '1.99',  '1.9',  '1'],
            [RoundingMode::DOWN,  '1.501',  '1.50',  '1.5',  '1'],
            [RoundingMode::DOWN,  '1.500',  '1.50',  '1.5',  '1'],
            [RoundingMode::DOWN,  '1.499',  '1.49',  '1.4',  '1'],
            [RoundingMode::DOWN,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::DOWN,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::DOWN,  '0.999',  '0.99',  '0.9',  '0'],
            [RoundingMode::DOWN,  '0.501',  '0.50',  '0.5',  '0'],
            [RoundingMode::DOWN,  '0.500',  '0.50',  '0.5',  '0'],
            [RoundingMode::DOWN,  '0.499',  '0.49',  '0.4',  '0'],
            [RoundingMode::DOWN,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::DOWN,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::DOWN, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::DOWN, '-0.499', '-0.49', '-0.4',  '0'],
            [RoundingMode::DOWN, '-0.500', '-0.50', '-0.5',  '0'],
            [RoundingMode::DOWN, '-0.501', '-0.50', '-0.5',  '0'],
            [RoundingMode::DOWN, '-0.999', '-0.99', '-0.9',  '0'],
            [RoundingMode::DOWN, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::DOWN, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::DOWN, '-1.499', '-1.49', '-1.4', '-1'],
            [RoundingMode::DOWN, '-1.500', '-1.50', '-1.5', '-1'],
            [RoundingMode::DOWN, '-1.501', '-1.50', '-1.5', '-1'],
            [RoundingMode::DOWN, '-1.999', '-1.99', '-1.9', '-1'],
            [RoundingMode::DOWN, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::DOWN, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::DOWN, '-2.499', '-2.49', '-2.4', '-2'],
            [RoundingMode::DOWN, '-2.500', '-2.50', '-2.5', '-2'],
            [RoundingMode::DOWN, '-2.501', '-2.50', '-2.5', '-2'],
            [RoundingMode::DOWN, '-2.999', '-2.99', '-2.9', '-2'],
            [RoundingMode::DOWN, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::DOWN, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::DOWN, '-3.499', '-3.49', '-3.4', '-3'],
            [RoundingMode::DOWN, '-3.500', '-3.50', '-3.5', '-3'],
            [RoundingMode::DOWN, '-3.501', '-3.50', '-3.5', '-3'],

            [RoundingMode::CEILING,  '3.501',  '3.51',  '3.6',  '4'],
            [RoundingMode::CEILING,  '3.500',  '3.50',  '3.5',  '4'],
            [RoundingMode::CEILING,  '3.499',  '3.50',  '3.5',  '4'],
            [RoundingMode::CEILING,  '3.001',  '3.01',  '3.1',  '4'],
            [RoundingMode::CEILING,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::CEILING,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::CEILING,  '2.501',  '2.51',  '2.6',  '3'],
            [RoundingMode::CEILING,  '2.500',  '2.50',  '2.5',  '3'],
            [RoundingMode::CEILING,  '2.499',  '2.50',  '2.5',  '3'],
            [RoundingMode::CEILING,  '2.001',  '2.01',  '2.1',  '3'],
            [RoundingMode::CEILING,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::CEILING,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::CEILING,  '1.501',  '1.51',  '1.6',  '2'],
            [RoundingMode::CEILING,  '1.500',  '1.50',  '1.5',  '2'],
            [RoundingMode::CEILING,  '1.499',  '1.50',  '1.5',  '2'],
            [RoundingMode::CEILING,  '1.001',  '1.01',  '1.1',  '2'],
            [RoundingMode::CEILING,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::CEILING,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::CEILING,  '0.501',  '0.51',  '0.6',  '1'],
            [RoundingMode::CEILING,  '0.500',  '0.50',  '0.5',  '1'],
            [RoundingMode::CEILING,  '0.499',  '0.50',  '0.5',  '1'],
            [RoundingMode::CEILING,  '0.001',  '0.01',  '0.1',  '1'],
            [RoundingMode::CEILING,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::CEILING, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::CEILING, '-0.499', '-0.49', '-0.4',  '0'],
            [RoundingMode::CEILING, '-0.500', '-0.50', '-0.5',  '0'],
            [RoundingMode::CEILING, '-0.501', '-0.50', '-0.5',  '0'],
            [RoundingMode::CEILING, '-0.999', '-0.99', '-0.9',  '0'],
            [RoundingMode::CEILING, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::CEILING, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::CEILING, '-1.499', '-1.49', '-1.4', '-1'],
            [RoundingMode::CEILING, '-1.500', '-1.50', '-1.5', '-1'],
            [RoundingMode::CEILING, '-1.501', '-1.50', '-1.5', '-1'],
            [RoundingMode::CEILING, '-1.999', '-1.99', '-1.9', '-1'],
            [RoundingMode::CEILING, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::CEILING, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::CEILING, '-2.499', '-2.49', '-2.4', '-2'],
            [RoundingMode::CEILING, '-2.500', '-2.50', '-2.5', '-2'],
            [RoundingMode::CEILING, '-2.501', '-2.50', '-2.5', '-2'],
            [RoundingMode::CEILING, '-2.999', '-2.99', '-2.9', '-2'],
            [RoundingMode::CEILING, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::CEILING, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::CEILING, '-3.499', '-3.49', '-3.4', '-3'],
            [RoundingMode::CEILING, '-3.500', '-3.50', '-3.5', '-3'],
            [RoundingMode::CEILING, '-3.501', '-3.50', '-3.5', '-3'],

            [RoundingMode::FLOOR,  '3.501',  '3.50',  '3.5',  '3'],
            [RoundingMode::FLOOR,  '3.500',  '3.50',  '3.5',  '3'],
            [RoundingMode::FLOOR,  '3.499',  '3.49',  '3.4',  '3'],
            [RoundingMode::FLOOR,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::FLOOR,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::FLOOR,  '2.999',  '2.99',  '2.9',  '2'],
            [RoundingMode::FLOOR,  '2.501',  '2.50',  '2.5',  '2'],
            [RoundingMode::FLOOR,  '2.500',  '2.50',  '2.5',  '2'],
            [RoundingMode::FLOOR,  '2.499',  '2.49',  '2.4',  '2'],
            [RoundingMode::FLOOR,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::FLOOR,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::FLOOR,  '1.999',  '1.99',  '1.9',  '1'],
            [RoundingMode::FLOOR,  '1.501',  '1.50',  '1.5',  '1'],
            [RoundingMode::FLOOR,  '1.500',  '1.50',  '1.5',  '1'],
            [RoundingMode::FLOOR,  '1.499',  '1.49',  '1.4',  '1'],
            [RoundingMode::FLOOR,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::FLOOR,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::FLOOR,  '0.999',  '0.99',  '0.9',  '0'],
            [RoundingMode::FLOOR,  '0.501',  '0.50',  '0.5',  '0'],
            [RoundingMode::FLOOR,  '0.500',  '0.50',  '0.5',  '0'],
            [RoundingMode::FLOOR,  '0.499',  '0.49',  '0.4',  '0'],
            [RoundingMode::FLOOR,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::FLOOR,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::FLOOR, '-0.001', '-0.01', '-0.1', '-1'],
            [RoundingMode::FLOOR, '-0.499', '-0.50', '-0.5', '-1'],
            [RoundingMode::FLOOR, '-0.500', '-0.50', '-0.5', '-1'],
            [RoundingMode::FLOOR, '-0.501', '-0.51', '-0.6', '-1'],
            [RoundingMode::FLOOR, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::FLOOR, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::FLOOR, '-1.001', '-1.01', '-1.1', '-2'],
            [RoundingMode::FLOOR, '-1.499', '-1.50', '-1.5', '-2'],
            [RoundingMode::FLOOR, '-1.500', '-1.50', '-1.5', '-2'],
            [RoundingMode::FLOOR, '-1.501', '-1.51', '-1.6', '-2'],
            [RoundingMode::FLOOR, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::FLOOR, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::FLOOR, '-2.001', '-2.01', '-2.1', '-3'],
            [RoundingMode::FLOOR, '-2.499', '-2.50', '-2.5', '-3'],
            [RoundingMode::FLOOR, '-2.500', '-2.50', '-2.5', '-3'],
            [RoundingMode::FLOOR, '-2.501', '-2.51', '-2.6', '-3'],
            [RoundingMode::FLOOR, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::FLOOR, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::FLOOR, '-3.001', '-3.01', '-3.1', '-4'],
            [RoundingMode::FLOOR, '-3.499', '-3.50', '-3.5', '-4'],
            [RoundingMode::FLOOR, '-3.500', '-3.50', '-3.5', '-4'],
            [RoundingMode::FLOOR, '-3.501', '-3.51', '-3.6', '-4'],

            [RoundingMode::HALF_UP,  '3.501',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_UP,  '3.500',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_UP,  '3.499',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_UP,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_UP,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_UP,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_UP,  '2.501',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_UP,  '2.500',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_UP,  '2.499',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_UP,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_UP,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_UP,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_UP,  '1.501',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_UP,  '1.500',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_UP,  '1.499',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_UP,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_UP,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_UP,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_UP,  '0.501',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_UP,  '0.500',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_UP,  '0.499',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_UP,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_UP,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_UP, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_UP, '-0.499', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_UP, '-0.500', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_UP, '-0.501', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_UP, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_UP, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_UP, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_UP, '-1.499', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_UP, '-1.500', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_UP, '-1.501', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_UP, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_UP, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_UP, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_UP, '-2.499', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_UP, '-2.500', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_UP, '-2.501', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_UP, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_UP, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_UP, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_UP, '-3.499', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_UP, '-3.500', '-3.50', '-3.5', '-4'],
            [RoundingMode::HALF_UP, '-3.501', '-3.50', '-3.5', '-4'],

            [RoundingMode::HALF_DOWN,  '3.501',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_DOWN,  '3.500',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_DOWN,  '3.499',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_DOWN,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_DOWN,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_DOWN,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_DOWN,  '2.501',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_DOWN,  '2.500',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_DOWN,  '2.499',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_DOWN,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_DOWN,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_DOWN,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_DOWN,  '1.501',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_DOWN,  '1.500',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_DOWN,  '1.499',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_DOWN,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_DOWN,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_DOWN,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_DOWN,  '0.501',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_DOWN,  '0.500',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_DOWN,  '0.499',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_DOWN,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_DOWN,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_DOWN, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_DOWN, '-0.499', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_DOWN, '-0.500', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_DOWN, '-0.501', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_DOWN, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_DOWN, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_DOWN, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_DOWN, '-1.499', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_DOWN, '-1.500', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_DOWN, '-1.501', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_DOWN, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_DOWN, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_DOWN, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_DOWN, '-2.499', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_DOWN, '-2.500', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_DOWN, '-2.501', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_DOWN, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_DOWN, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_DOWN, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_DOWN, '-3.499', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_DOWN, '-3.500', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_DOWN, '-3.501', '-3.50', '-3.5', '-4'],

            [RoundingMode::HALF_CEILING,  '3.501',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_CEILING,  '3.500',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_CEILING,  '3.499',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_CEILING,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_CEILING,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_CEILING,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_CEILING,  '2.501',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_CEILING,  '2.500',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_CEILING,  '2.499',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_CEILING,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_CEILING,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_CEILING,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_CEILING,  '1.501',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_CEILING,  '1.500',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_CEILING,  '1.499',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_CEILING,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_CEILING,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_CEILING,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_CEILING,  '0.501',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_CEILING,  '0.500',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_CEILING,  '0.499',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_CEILING,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_CEILING,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_CEILING, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_CEILING, '-0.499', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_CEILING, '-0.500', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_CEILING, '-0.501', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_CEILING, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_CEILING, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_CEILING, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_CEILING, '-1.499', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_CEILING, '-1.500', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_CEILING, '-1.501', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_CEILING, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_CEILING, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_CEILING, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_CEILING, '-2.499', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_CEILING, '-2.500', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_CEILING, '-2.501', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_CEILING, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_CEILING, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_CEILING, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_CEILING, '-3.499', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_CEILING, '-3.500', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_CEILING, '-3.501', '-3.50', '-3.5', '-4'],

            [RoundingMode::HALF_FLOOR,  '3.501',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_FLOOR,  '3.500',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_FLOOR,  '3.499',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_FLOOR,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_FLOOR,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_FLOOR,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_FLOOR,  '2.501',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_FLOOR,  '2.500',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_FLOOR,  '2.499',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_FLOOR,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_FLOOR,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_FLOOR,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_FLOOR,  '1.501',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_FLOOR,  '1.500',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_FLOOR,  '1.499',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_FLOOR,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_FLOOR,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_FLOOR,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_FLOOR,  '0.501',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_FLOOR,  '0.500',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_FLOOR,  '0.499',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_FLOOR,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_FLOOR,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_FLOOR, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_FLOOR, '-0.499', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_FLOOR, '-0.500', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_FLOOR, '-0.501', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_FLOOR, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.499', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_FLOOR, '-1.500', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_FLOOR, '-1.501', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_FLOOR, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.499', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_FLOOR, '-2.500', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_FLOOR, '-2.501', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_FLOOR, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.499', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_FLOOR, '-3.500', '-3.50', '-3.5', '-4'],
            [RoundingMode::HALF_FLOOR, '-3.501', '-3.50', '-3.5', '-4'],

            [RoundingMode::HALF_EVEN,  '3.501',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_EVEN,  '3.500',  '3.50',  '3.5',  '4'],
            [RoundingMode::HALF_EVEN,  '3.499',  '3.50',  '3.5',  '3'],
            [RoundingMode::HALF_EVEN,  '3.001',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_EVEN,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_EVEN,  '2.999',  '3.00',  '3.0',  '3'],
            [RoundingMode::HALF_EVEN,  '2.501',  '2.50',  '2.5',  '3'],
            [RoundingMode::HALF_EVEN,  '2.500',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_EVEN,  '2.499',  '2.50',  '2.5',  '2'],
            [RoundingMode::HALF_EVEN,  '2.001',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_EVEN,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_EVEN,  '1.999',  '2.00',  '2.0',  '2'],
            [RoundingMode::HALF_EVEN,  '1.501',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_EVEN,  '1.500',  '1.50',  '1.5',  '2'],
            [RoundingMode::HALF_EVEN,  '1.499',  '1.50',  '1.5',  '1'],
            [RoundingMode::HALF_EVEN,  '1.001',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_EVEN,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_EVEN,  '0.999',  '1.00',  '1.0',  '1'],
            [RoundingMode::HALF_EVEN,  '0.501',  '0.50',  '0.5',  '1'],
            [RoundingMode::HALF_EVEN,  '0.500',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_EVEN,  '0.499',  '0.50',  '0.5',  '0'],
            [RoundingMode::HALF_EVEN,  '0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_EVEN,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_EVEN, '-0.001',  '0.00',  '0.0',  '0'],
            [RoundingMode::HALF_EVEN, '-0.499', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_EVEN, '-0.500', '-0.50', '-0.5',  '0'],
            [RoundingMode::HALF_EVEN, '-0.501', '-0.50', '-0.5', '-1'],
            [RoundingMode::HALF_EVEN, '-0.999', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_EVEN, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_EVEN, '-1.001', '-1.00', '-1.0', '-1'],
            [RoundingMode::HALF_EVEN, '-1.499', '-1.50', '-1.5', '-1'],
            [RoundingMode::HALF_EVEN, '-1.500', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_EVEN, '-1.501', '-1.50', '-1.5', '-2'],
            [RoundingMode::HALF_EVEN, '-1.999', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_EVEN, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_EVEN, '-2.001', '-2.00', '-2.0', '-2'],
            [RoundingMode::HALF_EVEN, '-2.499', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_EVEN, '-2.500', '-2.50', '-2.5', '-2'],
            [RoundingMode::HALF_EVEN, '-2.501', '-2.50', '-2.5', '-3'],
            [RoundingMode::HALF_EVEN, '-2.999', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_EVEN, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_EVEN, '-3.001', '-3.00', '-3.0', '-3'],
            [RoundingMode::HALF_EVEN, '-3.499', '-3.50', '-3.5', '-3'],
            [RoundingMode::HALF_EVEN, '-3.500', '-3.50', '-3.5', '-4'],
            [RoundingMode::HALF_EVEN, '-3.501', '-3.50', '-3.5', '-4'],

            [RoundingMode::UNNECESSARY,  '3.501',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '3.500',  '3.50',  '3.5', null],
            [RoundingMode::UNNECESSARY,  '3.499',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '3.001',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '3.000',  '3.00',  '3.0',  '3'],
            [RoundingMode::UNNECESSARY,  '2.999',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '2.501',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '2.500',  '2.50',  '2.5', null],
            [RoundingMode::UNNECESSARY,  '2.499',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '2.001',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '2.000',  '2.00',  '2.0',  '2'],
            [RoundingMode::UNNECESSARY,  '1.999',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '1.501',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '1.500',  '1.50',  '1.5', null],
            [RoundingMode::UNNECESSARY,  '1.499',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '1.001',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '1.000',  '1.00',  '1.0',  '1'],
            [RoundingMode::UNNECESSARY,  '0.999',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '0.501',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '0.500',  '0.50',  '0.5', null],
            [RoundingMode::UNNECESSARY,  '0.499',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '0.001',    null,   null, null],
            [RoundingMode::UNNECESSARY,  '0.000',  '0.00',  '0.0',  '0'],
            [RoundingMode::UNNECESSARY, '-0.001',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-0.499',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-0.500', '-0.50', '-0.5', null],
            [RoundingMode::UNNECESSARY, '-0.501',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-0.999',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-1.000', '-1.00', '-1.0', '-1'],
            [RoundingMode::UNNECESSARY, '-1.001',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-1.499',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-1.500', '-1.50', '-1.5', null],
            [RoundingMode::UNNECESSARY, '-1.501',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-1.999',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-2.000', '-2.00', '-2.0', '-2'],
            [RoundingMode::UNNECESSARY, '-2.001',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-2.499',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-2.500', '-2.50', '-2.5', null],
            [RoundingMode::UNNECESSARY, '-2.501',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-2.999',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-3.000', '-3.00', '-3.0', '-3'],
            [RoundingMode::UNNECESSARY, '-3.001',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-3.499',    null,   null, null],
            [RoundingMode::UNNECESSARY, '-3.500', '-3.50', '-3.5', null],
            [RoundingMode::UNNECESSARY, '-3.501',    null,   null, null],
        ];
    }
}
