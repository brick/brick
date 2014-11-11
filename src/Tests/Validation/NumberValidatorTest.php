<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\NumberValidator;

/**
 * Unit tests for the number validator.
 */
class NumberValidatorTest extends AbstractTestCase
{
    public function testValidNumbers()
    {
        $validator = new NumberValidator();

        $this->doTestValidator($validator, [
            '1'    => [],
            '1.23' => [],
            'test' => ['validator.number.invalid'],
            ''     => ['validator.number.invalid'],
            ' 0'   => ['validator.number.invalid'],
            '0 '   => ['validator.number.invalid']
        ]);
    }

    public function testMin()
    {
        $validator = new NumberValidator();
        $validator->setMin(1);

        $this->doTestValidator($validator, [
            '0' => ['validator.number.min'],
            '1' => [],
            '2' => [],

            '0.99' => ['validator.number.min'],
            '1.00' => [],
            '1.01' => []
        ]);
    }

    public function testMax()
    {
        $validator = new NumberValidator();
        $validator->setMax(1);

        $this->doTestValidator($validator, [
            '0' => [],
            '1' => [],
            '2' => ['validator.number.max'],

            '0.99' => [],
            '1.00' => [],
            '1.01' => ['validator.number.max']
        ]);
    }

    public function testStep()
    {
        $validator = new NumberValidator();
        $validator->setStep(2);

        $this->doTestValidator($validator, [
            '0' => [],
            '1' => ['validator.number.step'],
            '2' => [],

            '1.99' => ['validator.number.step'],
            '2.00' => [],
            '2.01' => ['validator.number.step']
        ]);
    }
}
