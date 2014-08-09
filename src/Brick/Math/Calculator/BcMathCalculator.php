<?php

namespace Brick\Math\Calculator;

use Brick\Math\Calculator;

/**
 * Calculator implementation built around the bcmath library.
 */
class BcMathCalculator extends Calculator
{
    /**
     * {@inheritdoc}
     */
    public function add($a, $b)
    {
        return bcadd($a, $b, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function sub($a, $b)
    {
        return bcsub($a, $b, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function mul($a, $b)
    {
        return bcmul($a, $b, 0);
    }

    /**
     * {@inheritdoc}
     */
    public function div($a, $b)
    {
        $q = bcdiv($a, $b, 0);
        $r = bcmod($a, $b);

        return [$q, $r];
    }

    /**
     * {@inheritdoc}
     */
    public function pow($a, $e)
    {
        return bcpow($a, $e, 0);
    }
}
