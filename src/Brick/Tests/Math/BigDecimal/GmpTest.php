<?php

namespace Brick\Tests\Math\BigDecimal;

use Brick\Math\Internal\Calculator\GmpCalculator;

/**
 * @requires extension gmp
 */
class GmpTest extends AbstractTestCase
{
    /**
     * @inheritdoc
     */
    public function getCalculator()
    {
        return new GmpCalculator();
    }
}
