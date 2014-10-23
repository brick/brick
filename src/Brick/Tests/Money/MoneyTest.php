<?php

namespace Brick\Tests\Money;

use Brick\Money\Money;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;

/**
 * Unit tests for class Money.
 */
class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Money|string $expected
     * @param Money|string $actual
     */
    private function assertMoneyEquals($expected, $actual)
    {
        $expected = Money::parse($expected);
        $actual   = Money::parse($actual);

        $this->assertTrue(
            Money::parse($actual)->isEqualTo($expected),
            sprintf('Expected %s, got %s', $expected, $actual)
        );
    }

    /**
     * @dataProvider providerOfCents
     *
     * @param string  $currency
     * @param integer $cents
     * @param string  $expected
     */
    public function testOfCents($currency, $cents, $expected)
    {
        $this->assertMoneyEquals($expected, Money::ofCents($currency, $cents));
    }

    /**
     * @return array
     */
    public function providerOfCents()
    {
        return [
            ['EUR', 1, 'EUR 0.01'],
            ['USD', 1545, 'USD 15.45'],
            ['JPY', 600, 'JPY 600']
        ];
    }

    public function testPlus()
    {
        $money = Money::of('USD', '12.34');

        $this->assertMoneyEquals('USD 13.34', $money->plus(1));
        $this->assertMoneyEquals('USD 13.57', $money->plus('1.23'));
        $this->assertMoneyEquals('USD 24.68', $money->plus($money));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testPlusOutOfScaleThrowsException()
    {
        Money::of('USD', '12.34')->plus('0.001');
    }

    /**
     * @expectedException \Brick\Money\CurrencyMismatchException
     */
    public function testPlusDifferentCurrencyThrowsException()
    {
        Money::of('USD', '12.34')->plus(Money::of('EUR', '1'));
    }

    public function testMinus()
    {
        $money = Money::of('USD', '12.34');

        $this->assertMoneyEquals('USD 11.34', $money->minus(1));
        $this->assertMoneyEquals('USD 11.11', $money->minus('1.23'));
        $this->assertMoneyEquals('USD 0.00', $money->minus($money));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMinusOutOfScaleThrowsException()
    {
        Money::of('USD', '12.34')->minus('0.001');
    }

    /**
     * @expectedException \Brick\Money\CurrencyMismatchException
     */
    public function testMinusDifferentCurrencyThrowsException()
    {
        Money::of('USD', '12.34')->minus(Money::of('EUR', '1'));
    }

    public function testMultipliedBy()
    {
        $money = Money::of('USD', '12.34');

        $this->assertMoneyEquals('USD 24.68', $money->multipliedBy(2));
        $this->assertMoneyEquals('USD 18.51', $money->multipliedBy('1.5'));
        $this->assertMoneyEquals('USD 14.80', $money->multipliedBy('1.2', RoundingMode::DOWN));
        $this->assertMoneyEquals('USD 14.81', $money->multipliedBy(BigDecimal::of('1.2'), RoundingMode::UP));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMultipliedByOutOfScaleThrowsException()
    {
        Money::of('USD', '12.34')->multipliedBy('1.1');
    }

    public function testDividedBy()
    {
        $money = Money::of('USD', '12.34');

        $this->assertEquals('USD 6.17', $money->dividedBy(2));
        $this->assertEquals('USD 10.28', $money->dividedBy('1.2', RoundingMode::DOWN));
        $this->assertEquals('USD 10.29', $money->dividedBy(BigDecimal::of('1.2'), RoundingMode::UP));
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testDividedByOutOfScaleThrowsException()
    {
        Money::of('USD', '12.34')->dividedBy(3);
    }

    public function testIsZero()
    {
        $this->assertFalse(Money::of('USD', '-0.01')->isZero());
        $this->assertTrue(Money::of('USD', '0')->isZero());
        $this->assertFalse(Money::of('USD', '0.01')->isZero());
    }

    public function testIsPositive()
    {
        $this->assertFalse(Money::of('USD', '-0.01')->isPositive());
        $this->assertFalse(Money::of('USD', '0')->isPositive());
        $this->assertTrue(Money::of('USD', '0.01')->isPositive());
    }

    public function testIsPositiveOrZero()
    {
        $this->assertFalse(Money::of('USD', '-0.01')->isPositiveOrZero());
        $this->assertTrue(Money::of('USD', '0')->isPositiveOrZero());
        $this->assertTrue(Money::of('USD', '0.01')->isPositiveOrZero());
    }

    public function testIsNegative()
    {
        $this->assertTrue(Money::of('USD', '-0.01')->isNegative());
        $this->assertFalse(Money::of('USD', '0')->isNegative());
        $this->assertFalse(Money::of('USD', '0.01')->isNegative());
    }

    public function testIsNegativeOrZero()
    {
        $this->assertTrue(Money::of('USD', '-0.01')->isNegativeOrZero());
        $this->assertTrue(Money::of('USD', '0')->isNegativeOrZero());
        $this->assertFalse(Money::of('USD', '0.01')->isNegativeOrZero());
    }

    public function testGetAmountMajor()
    {
        $this->assertSame('123', Money::parse('USD 123.45')->getAmountMajor());
    }

    public function testGetAmountMinor()
    {
        $this->assertSame('45', Money::parse('USD 123.45')->getAmountMinor());
    }

    public function testGetAmountCents()
    {
        $this->assertSame('12345', Money::parse('USD 123.45')->getAmountCents());
    }
}
