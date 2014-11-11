<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\PatternValidator;

/**
 * Unit tests for the pattern validator.
 */
class PatternValidatorTest extends AbstractTestCase
{
    public function testPatternValidator()
    {
        $validator = new PatternValidator('[0-9a-z]{2}');

        $this->doTestValidator($validator, [
            'ab' => [],
            'a0' => [],
            '0a' => [],

            ''    => ['validator.pattern.no-match'],
            'a'   => ['validator.pattern.no-match'],
            'Ab'  => ['validator.pattern.no-match'],
            'aB'  => ['validator.pattern.no-match'],
            'AB'  => ['validator.pattern.no-match'],
            'abc' => ['validator.pattern.no-match'],
            ' ab' => ['validator.pattern.no-match'],
            'ab ' => ['validator.pattern.no-match']
        ]);
    }
}
