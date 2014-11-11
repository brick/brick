<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\DigitValidator;

/**
 * Unit tests for the digit validator.
 */
class DigitValidatorTest extends AbstractTestCase
{
    public function testDigitValidator()
    {
        $validator = new DigitValidator();

        $this->doTestValidator($validator, [
            '0123456789'                     => [],
            '012345678901234567890123456789' => [],

            ''   => ['validator.digit.invalid'],
            ' 0' => ['validator.digit.invalid'],
            '0 ' => ['validator.digit.invalid'],

            '123.456' => ['validator.digit.invalid'],
            '123A'    => ['validator.digit.invalid']
        ]);
    }
}
