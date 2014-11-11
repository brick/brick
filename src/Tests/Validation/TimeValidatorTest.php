<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator\TimeValidator;

/**
 * Unit tests for the time validator.
 */
class TimeValidatorTest extends AbstractTestCase
{
    public function testTimeValidator()
    {
        $validator = new TimeValidator();

        $this->doTestValidator($validator, [
            ''            => ['validator.time.invalid-format'],
            '0'           => ['validator.time.invalid-format'],
            '00'          => ['validator.time.invalid-format'],
            '00:'         => ['validator.time.invalid-format'],
            '00:0'        => ['validator.time.invalid-format'],
            '00:00'       => [],
            '00:00:'      => ['validator.time.invalid-format'],
            '00:00:0'     => ['validator.time.invalid-format'],
            '00:00:00'    => [],
            '00:00:00:'   => ['validator.time.invalid-format'],
            '00:00:00:0'  => ['validator.time.invalid-format'],
            '00:00:00:00' => ['validator.time.invalid-format'],

            ' 00:00' => ['validator.time.invalid-format'],
            '00:00 ' => ['validator.time.invalid-format'],

            '23:59' => [],
            '23:60' => ['validator.time.invalid-time'],
            '24:00' => ['validator.time.invalid-time'],

            '23:59:59' => [],
            '23:60:00' => ['validator.time.invalid-time'],
            '23:00:60' => ['validator.time.invalid-time'],
            '24:00:00' => ['validator.time.invalid-time']
        ]);
    }
}
