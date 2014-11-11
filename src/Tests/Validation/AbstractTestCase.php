<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator;

/**
 * Base class for validation tests.
 */
abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param Validator $validator The validator under test.
     * @param array     $tests     The values to test, along with the expected results.
     *
     * @return void
     */
    final protected function doTestValidator(Validator $validator, array $tests)
    {
        foreach ($tests as $value => $expectedFailureMessageKeys) {
            $this->assertSame($expectedFailureMessageKeys === [], $validator->isValid($value));
            $this->assertSame($expectedFailureMessageKeys, array_keys($validator->getFailureMessages()));
        }
    }
}
