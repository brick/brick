<?php

namespace Brick\Tests\Type;

use Brick\Math\Decimal;

/**
 * Unit tests for class Decimal.
 */
class DecimalTest extends \PHPUnit_Framework_TestCase
{
    public function testEquality()
    {
        $a = Decimal::of('1.0');
        $b = Decimal::of('1.00');

        $this->assertTrue($a->isEqualTo($b));
    }

    public function testDecimal()
    {
        $number1 = Decimal::of('1.0123456789');
        $number2 = Decimal::of('2.0987654321');

        $expectedSum = Decimal::of('3.111111111');
        $expectedDifference = Decimal::of('-1.0864197532');
        $expectedProduct = Decimal::of('2.12467611621112635269');

        // Check addition / subtraction
        $this->assertTrue($number1->plus($number2)->isEqualTo($expectedSum));
        $this->assertTrue($number1->minus($number2)->isEqualTo($expectedDifference));
        $this->assertTrue($number1->plus($number2)->minus($number2)->isEqualTo($number1));

        // Check multiplication
        $this->assertTrue($number1->multipliedBy($number2)->isEqualTo($expectedProduct));

        $times3 = $number1->multipliedBy(Decimal::of(3));
        $this->assertTrue($number1->plus($number1)->plus($number1)->isEqualTo($times3));

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
     * @expectedException \RuntimeException
     */
    public function testDivisionByZero()
    {
        Decimal::one()->dividedBy(Decimal::zero());
    }

    /**
     * @expectedException \RuntimeException
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

        $this->assertTrue($p->dividedBy($q, 50, false)->isEqualTo($r));
    }

    public function testDivisionWithNoRoundingNecessary()
    {
        $p = Decimal::of('0.123456789');
        $q = Decimal::of('0.00244140625');
        $r = Decimal::of('50.5679007744');

        $this->assertTrue($p->dividedBy($q, 10)->isEqualTo($r));
    }
}
