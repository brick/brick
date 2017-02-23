<?php

namespace Brick\Tests\Validation;

use Brick\Validation\Validator;

use PHPUnit\Framework\TestCase;

/**
 * Base class for validation tests.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @param Validator $validator The validator under test.
     * @param array     $tests     The values to test, along with the expected results.
     *
     * @return void
     */
    final protected function doTestValidator(Validator $validator, array $tests)
    {
        $testNumber = 1;

        foreach ($tests as $value => $expectedFailureMessageKeys) {
            $message = sprintf(
                'Test number %d: expected %s, got %s',
                $testNumber,
                json_encode($expectedFailureMessageKeys),
                json_encode(array_keys($validator->getFailureMessages()))
            );

            $this->assertSame($expectedFailureMessageKeys === [], $validator->isValid($value), $message);
            $this->assertSame($expectedFailureMessageKeys, array_keys($validator->getFailureMessages()), $message);

            $testNumber++;
        }
    }
}
