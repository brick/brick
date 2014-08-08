<?php

namespace Brick\Math\Calculator;

use Brick\Math\Calculator;

/**
 * Calculator implementation using only native PHP code.
 */
class NativeCalculator extends Calculator
{
    /**
     * The max number of digits the platform can natively add, subtract or divide without overflow.
     *
     * @var integer
     */
    private $maxDigitsAddDiv = 0;

    /**
     * The max number of digits the platform can natively multiply without overflow.
     *
     * @var integer
     */
    private $maxDigitsMul = 0;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        switch (PHP_INT_SIZE) {
            case 4:
                $this->maxDigitsAddDiv = 9;
                $this->maxDigitsMul = 4;
                break;

            case 8:
                $this->maxDigitsAddDiv = 18;
                $this->maxDigitsMul = 9;
                break;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add($a, $b)
    {
        $this->init($a, $b, $aDig, $bDig, $aNeg, $bNeg, $aLen, $bLen);

        if ($aLen <= $this->maxDigitsAddDiv && $bLen <= $this->maxDigitsAddDiv) {
            return (string) ($a + $b);
        }

        if ($aNeg === $bNeg) {
            $result = $this->doAdd($aDig, $bDig, $aLen, $bLen);
        } else {
            $result = $this->doSub($aDig, $bDig, $aLen, $bLen);
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
        $this->init($a, $b, $aDig, $bDig, $aNeg, $bNeg, $aLen, $bLen);

        if ($aLen <= $this->maxDigitsMul && $bLen <= $this->maxDigitsMul) {
            return (string) ($a * $b);
        }

        $result = $this->doMul($aDig, $bDig, $aLen, $bLen);

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
        $this->init($a, $b, $aDig, $bDig, $aNeg, $bNeg, $aLen, $bLen);

        if ($aLen <= $this->maxDigitsAddDiv && $bLen <= $this->maxDigitsAddDiv) {
            $r = (string) ($a % $b);

            return (string) (($a - $r) / $b);
        }

        $result = $this->doDiv($aDig, $bDig, $aLen, $bLen, $r);

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
     * @param string  $a The first operand.
     * @param string  $b The second operand.
     * @param integer $x The length of the first operand.
     * @param integer $y The length of the second operand.
     *
     * @return string
     */
    private function doAdd($a, $b, $x, $y)
    {
        $length = $this->pad($a, $b, $x, $y);

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
     * @param string  $a The first operand.
     * @param string  $b The second operand.
     * @param integer $x The length of the first operand.
     * @param integer $y The length of the second operand.
     *
     * @return string
     */
    private function doSub($a, $b, $x, $y)
    {
        $cmp = $this->doCmp($a, $b, $x, $y);

        if ($cmp === 0) {
            return '0';
        }

        $invert = ($cmp === -1);

        if ($invert) {
            $c = $a;
            $a = $b;
            $b = $c;

            $z = $x;
            $x = $y;
            $y = $z;
        }

        $length = $this->pad($a, $b, $x, $y);

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

        if ($invert) {
            $result = $this->invert($result);
        }

        return $result;
    }

    /**
     * Performs the multiplication of two non-signed large integers.
     *
     * @param string  $a The first operand.
     * @param string  $b The second operand.
     * @param integer $x The length of the first operand.
     * @param integer $y The length of the second operand.
     *
     * @return string
     */
    private function doMul($a, $b, $x, $y)
    {
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

            $line = rtrim($line, '0');

            if ($line !== '') {
                $result = $this->add($result, strrev($line));
            }
        }

        return $result;
    }

    /**
     * Performs the division of two non-signed large integers.
     *
     * @param string  $a The first operand.
     * @param string  $b The second operand.
     * @param integer $x The length of the first operand.
     * @param integer $y The length of the second operand.
     * @param string  $r A variable to store the remainder of the division.
     *
     * @return string
     */
    private function doDiv($a, $b, $x, $y, & $r)
    {
        if ($b === '1') {
            $r = '0';

            return $a;
        }

        $cmp = $this->doCmp($a, $b, $x, $y);

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

        for (;;) {
            $focus = substr($a, 0, $y);
            $cmp = $this->doCmp($focus, $b, strlen($focus), strlen($b));

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
     * @param string  $a The first operand.
     * @param string  $b The second operand.
     * @param integer $x The length of the first operand.
     * @param integer $y The length of the second operand.
     *
     * @return integer [-1, 0, 1]
     */
    private function doCmp($a, $b, $x, $y)
    {
        if ($x > $y) {
            return 1;
        }
        if ($x < $y) {
            return -1;
        }

        for ($i = 0; $i < $x; $i++) {
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
     * @param string  $a    The first operand.
     * @param string  $b    The second operand.
     * @param string  $aDig A variable to store the digits of the first operand.
     * @param string  $bDig A variable to store the digits of the second operand.
     * @param boolean $aNeg A variable to store whether the first operand is negative.
     * @param boolean $bNeg A variable to store whether the second operand is negative.
     * @param boolean $aLen A variable to store the number of digits in the first operand.
     * @param boolean $bLen A variable to store the number of digits in the second operand.
     *
     * @return void
     */
    private function init($a, $b, & $aDig, & $bDig, & $aNeg, & $bNeg, & $aLen, & $bLen)
    {
        $aNeg = ($a[0] === '-');
        $bNeg = ($b[0] === '-');

        $aLen = strlen($a);
        $bLen = strlen($b);

        if ($aNeg) {
            $aDig = substr($a, 1);
            $aLen--;
        } else {
            $aDig = $a;
        }

        if ($bNeg) {
            $bDig = substr($b, 1);
            $bLen--;
        } else {
            $bDig = $b;
        }
    }

    /**
     * Returns the given number with the sign changed.
     *
     * @param string $n The number to invert.
     *
     * @return string The inverted number.
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
     * Pads the left of one of the given numbers with zeros if necessary to make both numbers the same length.
     *
     * The numbers must only consist of digits, without leading minus sign.
     *
     * @param string  $a The first operand.
     * @param string  $b The second operand.
     * @param integer $x The length of the first operand.
     * @param integer $y The length of the second operand.
     *
     * @return integer The length of both strings.
     */
    private function pad(& $a, & $b, $x, $y)
    {
        $length = $x > $y ? $x : $y;

        for (; $x < $length; $x++) {
            $a = '0' . $a;
        }
        for (; $y < $length; $y++) {
            $b = '0' . $b;
        }

        return $length;
    }
}
