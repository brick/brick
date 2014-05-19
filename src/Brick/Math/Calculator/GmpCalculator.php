<?php

namespace Brick\Math\Calculator;

use Brick\Math\Calculator;

/**
 * Calculator implementation built around the GMP library.
 */
class GmpCalculator extends Calculator
{
    /**
     * {@inheritdoc}
     */
    public function add($a, $b)
    {
        return gmp_strval(gmp_add($a, $b));
    }

    /**
     * {@inheritdoc}
     */
    public function sub($a, $b)
    {
        return gmp_strval(gmp_sub($a, $b));
    }

    /**
     * {@inheritdoc}
     */
    public function mul($a, $b)
    {
        return gmp_strval(gmp_mul($a, $b));
    }

    /**
     * {@inheritdoc}
     */
    public function div($a, $b, & $r)
    {
        list ($q, $r) = gmp_div_qr($a, $b);

        $r = gmp_strval($r);

        return gmp_strval($q);
    }
}
