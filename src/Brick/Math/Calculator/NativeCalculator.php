<?php

namespace Brick\Math\Calculator;

use Brick\Math\Calculator;

/**
 * Calculator implementation using only native PHP code.
 */
class NativeCalculator extends Calculator
{
    /**
     * {@inheritdoc}
     */
    public function add($a, $b)
    {
        $this->init($a, $b, $aNeg, $bNeg);

        if ($aNeg === $bNeg) {
            $result = $this->doAdd($a, $b);
        } else {
            $result = $this->doSub($a, $b);
        }

        if ($aNeg) {
            $result = $this->invert($result);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function sub($a, $b)
    {
        return $this->add($a, $this->invert($b));
    }

    /**
     * {@inheritdoc}
     */
    public function mul($a, $b)
    {
        $this->init($a, $b, $aNeg, $bNeg);

        $result = $this->doMul($a, $b);

        if ($aNeg !== $bNeg) {
            $result = $this->invert($result);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function div($a, $b, & $r)
    {
        $this->init($a, $b, $aNeg, $bNeg);

        $result = $this->doDiv($a, $b, $r);

        if ($aNeg !== $bNeg) {
            $result = $this->invert($result);
        }

        if ($aNeg) {
            $r = $this->invert($r);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function pow($a, $e)
    {
        if ($e === 0) {
            return '1';
        }

        if ($e === 1) {
            return $a;
        }

        $odd = $e % 2;
        $e -= $odd;

        $aa = $this->mul($a, $a);
        $result = $this->pow($aa, $e / 2);

        if ($odd === 1) {
            $result = $this->mul($result, $a);
        }

        return $result;
    }

    /**
     * Performs the addition of two non-signed large integers.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    private function doAdd($a, $b)
    {
        $length = $this->pad($a, $b);

        $carry = 0;
        $result = '';

        for ($i = $length - 1; $i >= 0; $i--) {
            $sum = $a[$i] + $b[$i] + $carry;

            if ($sum >= 10) {
                $carry = 1;
                $sum -= 10;
            } else {
                $carry = 0;
            }

            $result .= $sum;
        }

        if ($carry !== 0) {
            $result .= $carry;
        }

        return strrev($result);
    }
    /**
     * Performs the subtraction of two non-signed large integers.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    private function doSub($a, $b)
    {
        $cmp = $this->doCmp($a, $b);

        if ($cmp == 1) {
            return $this->doDoSub($a, $b);
        } elseif ($cmp == -1) {
            return $this->invert($this->doDoSub($b, $a));
        }

        return '0';
    }

    /**
     * Performs the subtraction of two non-signed large integers.
     *
     * *The second number must be lower than or equal to the first number.*
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    private function doDoSub($a, $b)
    {
        $length = $this->pad($a, $b);

        $carry = 0;
        $result = '';

        for ($i = $length - 1; $i >= 0; $i--) {
            $sum = $a[$i] - $b[$i] - $carry;

            if ($sum < 0) {
                $carry = 1;
                $sum += 10;
            } else {
                $carry = 0;
            }

            $result .= $sum;
        }

        $result = strrev($result);
        $result = ltrim($result, '0');

        if ($result === '') {
            return '0';
        }

        return $result;
    }

    /**
     * Performs the multiplication of two non-signed large integers.
     *
     * @param string $a
     * @param string $b
     *
     * @return string
     */
    private function doMul($a, $b)
    {
        $x = strlen($a);
        $y = strlen($b);

        $result = '0';

        for ($i = $x - 1; $i >= 0; $i--) {
            $line = str_repeat('0', $x - 1 - $i);
            $carry = 0;
            for ($j = $y - 1; $j >= 0; $j--) {
                $mul = $b[$j] * $a[$i] + $carry;
                $digit = $mul % 10;
                $carry = ($mul - $digit) / 10;
                $line .= $digit;
            }

            if ($carry !== 0) {
                $line .= $carry;
            }

            $line = strrev($line);
            $line = $this->trim($line);

            $result = $this->add($result, $line);
        }

        return $result;
    }

    /**
     * Performs the division of two non-signed large integers.
     *
     * @param string $a
     * @param string $b
     * @param string $r
     *
     * @return string
     */
    private function doDiv($a, $b, & $r)
    {
        if ($b === '1') {
            $r = '0';

            return $a;
        }

        $x = strlen($a);
        $y = strlen($b);

        // will not overflow an integer
        if ($x <= 9 && $y <= 9) {
            $r = (string) ($a % $b);

            return (string) (($a - $r) / $b);
        }

        $cmp = $this->doCmp($a, $b);

        if ($cmp === -1) {
            $r = $a;

            return '0';
        }

        if ($cmp === 0) {
            $r = '0';

            return '1';
        }

        // we now know that a > b && x >= y

        $q = '0';

        for ($g = 0; $g <= 1000; $g++) {
            $focus = substr($a, 0, $y);
            $cmp = $this->doCmp($focus, $b);

            if ($cmp === -1) {
                if ($y >= $x) {
                    $r = $a;

                    return $q;
                }

                $y++;
                continue;
            }

            $zeros = str_repeat('0', $x - $y);

            $diff = $this->sub($a, $b . $zeros);

            $q = $this->add($q, '1' . $zeros);
            $a = $diff;

            if ($a === '0') {
                $r = '0';

                return $q;
            }

            $x = strlen($a);
            $y = strlen($b);
        }
    }

    /**
     * Compares two non-signed large numbers.
     *
     * @param string $a
     * @param string $b
     *
     * @return integer [-1, 0, 1]
     */
    private function doCmp($a, $b)
    {
        $la = strlen($a);
        $lb = strlen($b);

        if ($la > $lb) {
            return 1;
        }
        if ($la < $lb) {
            return -1;
        }

        for ($i = 0; $i < $la; $i++) {
            if ($a[$i] > $b[$i]) {
                return 1;
            }
            if ($a[$i] < $b[$i]) {
                return -1;
            }
        }

        return 0;
    }

    /**
     * Initializes the variables needed by the public methods.
     *
     * @param string  $a    The first operand; optional minus sign will be removed.
     * @param string  $b    The second operand; optional minus sign will be removed.
     * @param boolean $aNeg Whether the first operand is negative.
     * @param boolean $bNeg Whether the second operand is negative.
     *
     * @return void
     */
    private function init(& $a, & $b, & $aNeg, & $bNeg)
    {
        $aNeg = ($a[0] === '-');
        $bNeg = ($b[0] === '-');

        if ($aNeg) {
            $a = substr($a, 1);
        }

        if ($bNeg) {
            $b = substr($b, 1);
        }
    }

    /**
     * Returns the given number with the sign changed.
     *
     * @param string $n
     *
     * @return string
     */
    private function invert($n)
    {
        if ($n === '0') {
            return '0';
        }

        if ($n[0] !== '-') {
            return '-' . $n;
        }

        return substr($n, 1);
    }

    /**
     * Trims leading zeros from a non-signed large number.
     *
     * @param string $number
     *
     * @return string
     */
    private function trim($number)
    {
        $number = ltrim($number, '0');

        return $number === '' ? '0' : $number;
    }

    /**
     * Pads `$a` or `$b` with zeros on the left to make them the same length.
     *
     * @param string $a
     * @param string $b
     *
     * @return integer The length of both strings.
     */
    private function pad(& $a, & $b)
    {
        $la = strlen($a);
        $lb = strlen($b);

        $length = $la > $lb ? $la : $lb;

        if ($la < $length) {
            $a = str_pad($a, $length, '0', STR_PAD_LEFT);
        }
        if ($lb < $length) {
            $b = str_pad($b, $length, '0', STR_PAD_LEFT);
        }

        return $length;
    }
}
