<?php

namespace Brick\Tests\Money;

use Brick\Money\Money;
use Brick\Locale\Currency;
use Brick\Math\RoundingMode;

/**
 * Unit test for class Money
 */
class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Brick\Money\Money
     */
    private $money;

    /**
     * @var \Brick\Locale\Currency
     */
    private $currency;

    public function setUp()
    {
        $this->currency = Currency::of('EUR');
        $this->money = Money::of($this->currency, 10);
    }

    public function testPlus()
    {
        $newMoney = Money::of($this->currency, '5.50');

        $this->assertTrue($this->money->isGreaterThanOrEqualTo($newMoney));

        $this->money = $this->money->plus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '15.50');
    }

    public function testMinus()
    {
        $newMoney = Money::of($this->currency, '5.50');

        $this->money = $this->money->minus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '4.50');
    }

    public function testMultipliedBy()
    {
        $this->money = $this->money->multipliedBy(5);
        $this->assertEquals($this->money->getAmount()->toString(), '50.00');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMultipliedByOutOfScaleThrowsException()
    {
        $this->money->multipliedBy('0.0001');
    }

    public function testDividedBy()
    {
        $this->assertEquals('5.00', $this->money->dividedBy(2)->getAmount()->toString());
        $this->assertEquals('3.33', $this->money->dividedBy(3, RoundingMode::DOWN)->getAmount()->toString());
        $this->assertEquals('3.34', $this->money->dividedBy(3, RoundingMode::UP)->getAmount()->toString());
    }

    /**
     * @expectedException \Brick\Math\ArithmeticException
     */
    public function testDividedByOutOfScaleThrowsException()
    {
        $this->money->dividedBy(3);
    }

    public function testAdjustments()
    {
        $this->assertFalse($this->money->isZero());
        $this->assertFalse($this->money->isNegative());

        $newMoney = Money::of($this->currency, 10);

        $this->money = $this->money->minus($newMoney);
        $this->assertTrue($this->money->isZero());

        $this->money = $this->money->minus($newMoney);
        $this->assertTrue($this->money->isNegative());
    }

    /**
     * @expectedException \Brick\Money\CurrencyMismatchException
     */
    public function testDifferentCurrenciesThrowException()
    {
        $eur = Money::of(Currency::of('EUR'), 1);
        $usd = Money::of(Currency::of('USD'), 1);

        $eur->plus($usd);
    }
}
