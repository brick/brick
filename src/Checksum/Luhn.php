<?php

namespace Brick\Checksum;

/**
 * Luhn algorithm calculations.
 */
class Luhn
{
    /**
     * Computes and returns the check digit of a number.
     *
     * @param string $number
     *
     * @return integer
     *
     * @throws \InvalidArgumentException
     */
    public static function getCheckDigit($number)
    {
        if (! ctype_digit($number)) {
            throw new \InvalidArgumentException('The number must be a string of digits');
        }

        $checksum = self::checksum($number . '0');

        return ($checksum == 0) ? 0 : 10 - $checksum;
    }

    /**
     * Checks that a number is valid.
     *
     * @param string $number
     *
     * @return boolean
     */
    public static function isValid($number)
    {
        return ctype_digit($number) ? (self::checksum($number) == 0) : false;
    }

    /**
     * Computes the checksum of a number.
     *
     * @param string $number The number, validated as a string of digits.
     *
     * @return integer
     */
    private static function checksum($number)
    {
        $number = strrev($number);
        $sum = 0;

        for ($i = 0; $i < strlen($number); $i++) {
            $value = $number[$i] * ($i % 2 + 1);
            $sum += ($value >= 10 ? $value - 9 : $value);
        }

        return $sum % 10;
    }
}
