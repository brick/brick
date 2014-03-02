<?php

namespace Brick\Tests\Money;

use Brick\Money\Money;
use Brick\Locale\Currency;
use Brick\Math\Decimal;
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
        $this->money = Money::of($this->currency, Decimal::of(10));
    }

    public function testPlus()
    {
        $newMoney = Money::of($this->currency, Decimal::of('5.50'));

        $this->assertTrue($this->money->isGreaterThanOrEqualTo($newMoney));

        $this->money = $this->money->plus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '15.50');
    }

    public function testMinus()
    {
        $newMoney = Money::of($this->currency, Decimal::of('5.50'));

        $this->money = $this->money->minus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '4.50');
    }

    public function testMultipliedBy()
    {
        $this->money = $this->money->multipliedBy(Decimal::of(5));
        $this->assertEquals($this->money->getAmount()->toString(), '50.00');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMultipliedByOutOfScaleThrowsException()
    {
        $this->money->multipliedBy(Decimal::of('0.0001'));
    }

    public function testDividedBy()
    {
        $this->assertEquals('5.00', $this->money->dividedBy(Decimal::of(2))->getAmount()->toString());
        $this->assertEquals('3.33', $this->money->dividedBy(Decimal::of(3), RoundingMode::DOWN)->getAmount()->toString());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDividedByOutOfScaleThrowsException()
    {
        $this->money->dividedBy(Decimal::of(3));
    }

    public function testAdjustments()
    {
        $this->assertFalse($this->money->isZero());
        $this->assertFalse($this->money->isNegative());

        $newMoney = Money::of($this->currency, Decimal::of(10));

        $this->money = $this->money->minus($newMoney);
        $this->assertTrue($this->money->isZero());

        $this->money = $this->money->minus($newMoney);
        $this->assertTrue($this->money->isNegative());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDifferentCurrenciesThrowException()
    {
        $eur = Money::of(Currency::of('EUR'), Decimal::one());
        $usd = Money::of(Currency::of('USD'), Decimal::one());

        $eur->plus($usd);
    }
}
