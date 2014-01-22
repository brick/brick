<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\NumberValidator;

/**
 * Unit tests for the number validator.
 */
class NumberValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidNumbers()
    {
        $validator = new NumberValidator();

        $this->assertTrue($validator->validate('1')->isSuccess());
        $this->assertTrue($validator->validate('1.23')->isSuccess());
        $this->assertFalse($validator->validate('test')->isSuccess());
    }

    public function testMin()
    {
        $validator = new NumberValidator();
        $validator->setMin(1);

        $this->assertFalse($validator->validate('0')->isSuccess());
        $this->assertTrue($validator->validate('1')->isSuccess());
        $this->assertTrue($validator->validate('2')->isSuccess());
    }

    public function testMax()
    {
        $validator = new NumberValidator();
        $validator->setMax(1);

        $this->assertTrue($validator->validate('0')->isSuccess());
        $this->assertTrue($validator->validate('1')->isSuccess());
        $this->assertFalse($validator->validate('2')->isSuccess());
    }

    public function testStep()
    {
        $validator = new NumberValidator();
        $validator->setStep(2);

        $this->assertTrue($validator->validate('0')->isSuccess());
        $this->assertFalse($validator->validate('1')->isSuccess());
        $this->assertTrue($validator->validate('2')->isSuccess());
    }
}
