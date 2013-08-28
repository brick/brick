<?php

namespace Brick\Tests\Money;

use Brick\Money\Money;
use Brick\Locale\Currency;
use Brick\Math\Decimal;

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
        $this->currency = Currency::getInstance('EUR');
        $this->money = new Money($this->currency, Decimal::of(10));
    }

    public function testPlus()
    {
        $newMoney = new Money($this->currency, Decimal::of('5.50'));

        $this->assertTrue($this->money->isGreaterThanOrEqualTo($newMoney));

        $this->money = $this->money->plus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '15.50');
    }

    public function testMinus()
    {
        $newMoney = new Money($this->currency, Decimal::of('5.50'));

        $this->money = $this->money->minus($newMoney);
        $this->assertEquals($this->money->getAmount()->toString(), '4.50');
    }

    public function testMultipliedBy()
    {
        $this->money = $this->money->multipliedBy(5);
        $this->assertEquals($this->money->getAmount()->toString(), '50.00');
    }

    public function testAdjustments()
    {
        $this->assertFalse($this->money->isZero());
        $this->assertFalse($this->money->isNegative());

        $newMoney = new Money($this->currency, Decimal::of(10));

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
        $eur = new Money(Currency::getInstance('EUR'), Decimal::one());
        $usd = new Money(Currency::getInstance('USD'), Decimal::one());

        $eur->plus($usd);
    }
}
