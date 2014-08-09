<?php

namespace Brick\Tests\Math\BigDecimal;

use Brick\Math\Internal\Calculator\BcMathCalculator;

/**
 * @requires extension bcmath
 */
class BcMathTest extends AbstractTestCase
{
    /**
     * @inheritdoc
     */
    public function getCalculator()
    {
        return new BcMathCalculator();
    }
}
