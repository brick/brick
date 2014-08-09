<?php

namespace Brick\Tests\Math\BigDecimal;

use Brick\Math\Internal\Calculator\NativeCalculator;

/**
 * Runs the BigDecimal tests using the native calculator.
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
