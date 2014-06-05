<?php

namespace Brick\Tests\Math\Decimal;

use Brick\Math\Calculator\BcMathCalculator;

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
