<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\LengthValidator;

/**
 * Unit tests for the length validator.
 */
class LengthValidatorTest extends AbstractTestCase
{
    public function testNoConstraintsByDefault()
    {
        $validator = new LengthValidator();

        $this->doTestValidator($validator, [
            ''     => [],
            'a'    => [],
            'ab'   => [],
            'abc'  => [],
            'abcd' => []
        ]);
    }

    public function testMinLength()
    {
        $validator = new LengthValidator();
        $validator->setMinLength(2);

        $this->doTestValidator($validator, [
            ''     => ['validator.length.too-short'],
            'a'    => ['validator.length.too-short'],
            'ab'   => [],
            'abc'  => [],
            'abcd' => []
        ]);
    }

    public function testMaxLength()
    {
        $validator = new LengthValidator();
        $validator->setMaxLength(2);

        $this->doTestValidator($validator, [
            ''     => [],
            'a'    => [],
            'ab'   => [],
            'abc'  => ['validator.length.too-long'],
            'abcd' => ['validator.length.too-long']
        ]);
    }

    public function testMinAndMaxLength()
    {
        $validator = new LengthValidator();
        $validator->setMinLength(1);
        $validator->setMaxLength(3);

        $this->doTestValidator($validator, [
            ''     => ['validator.length.too-short'],
            'a'    => [],
            'ab'   => [],
            'abc'  => [],
            'abcd' => ['validator.length.too-long']
        ]);
    }

    public function testExactLength()
    {
        $validator = new LengthValidator();
        $validator->setLength(2);

        $this->doTestValidator($validator, [
            ''     => ['validator.length.too-short'],
            'a'    => ['validator.length.too-short'],
            'ab'   => [],
            'abc'  => ['validator.length.too-long'],
            'abcd' => ['validator.length.too-long']
        ]);
    }
}
