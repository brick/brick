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
        $a = Decimal::fromString('1.0');
        $b = Decimal::fromString('1.00');

        $this->assertTrue($a->isEqualTo($b));
    }

    public function testDecimal()
    {
        $number1 = Decimal::fromString('1.0123456789');
        $number2 = Decimal::fromString('2.0987654321');

        $expectedSum = Decimal::fromString('3.111111111');
        $expectedDifference = Decimal::fromString('-1.0864197532');
        $expectedProduct = Decimal::fromString('2.12467611621112635269');

        // Check addition / subtraction
        $this->assertTrue($number1->add($number2)->isEqualTo($expectedSum));
        $this->assertTrue($number1->subtract($number2)->isEqualTo($expectedDifference));
        $this->assertTrue($number1->add($number2)->subtract($number2)->isEqualTo($number1));

        // Check multiplication
        $this->assertTrue($number1->multiply($number2)->isEqualTo($expectedProduct));

        $times3 = $number1->multiply(Decimal::fromInteger(3));
        $this->assertTrue($number1->add($number1)->add($number1)->isEqualTo($times3));

        // Check negation
        $this->assertTrue($number1->negate()->negate()->isEqualTo($number1));
        $this->assertTrue($number1->negate()->isNegative());

        // Check absolute value
        $this->assertTrue($number1->negate()->abs()->isEqualTo($number1));
        $this->assertTrue($number2->negate()->abs()->isEqualTo($number2->abs()));

        // Check positive test
        $this->assertTrue($number1->isPositive());
        $this->assertFalse($number1->subtract($number1)->isPositive());
        $this->assertTrue($number1->subtract($number1)->isPositiveOrZero());
        $this->assertFalse($number1->negate()->isPositive());
        $this->assertTrue($number1->negate()->isNegative());

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
        Decimal::one()->divide(Decimal::zero());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testDivisionWithRoundingNecessary()
    {
        $p = Decimal::fromString('1.234');
        $q = Decimal::fromString('123.456');

        $p->divide($q);
    }

    public function testDivisionWithRounding()
    {
        $p = Decimal::fromString('1.234');
        $q = Decimal::fromString('123.456');
        $r = Decimal::fromString('0.00999546397096941420425090720580611715914981855883');

        $this->assertTrue($p->divide($q, 50, false)->isEqualTo($r));
    }

    public function testDivisionWithNoRoundingNecessary()
    {
        $p = Decimal::fromString('0.123456789');
        $q = Decimal::fromString('0.00244140625');
        $r = Decimal::fromString('50.5679007744');

        $this->assertTrue($p->divide($q, 10)->isEqualTo($r));
    }
}
