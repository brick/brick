<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\ForbiddenCharsValidator;

/**
 * Unit tests for the forbidden chars validator.
 */
class ForbiddenCharsValidatorTest extends AbstractTestCase
{
    public function testForbiddenCharsValidator()
    {
        $validator = new ForbiddenCharsValidator('<>');

        $this->doTestValidator($validator, [
            ''     => [],
            'abc'  => [],
            'a b'  => [],
            'a<b'  => ['validator.forbidden-chars'],
            'a>b'  => ['validator.forbidden-chars'],
            '<a>'  => ['validator.forbidden-chars'],
            '</a>' => ['validator.forbidden-chars'],
        ]);
    }
}
