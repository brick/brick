<?php

namespace Brick\Tests\Math\BigInteger;

use Brick\Math\Internal\Calculator\NativeCalculator;

/**
 * Runs the BigInteger tests using the native calculator.
 */
class NativeTest extends AbstractTestCase
{
    /**
     * @inheritdoc
     */
    public function getCalculator()
    {
        return new NativeCalculator();
    }
}
