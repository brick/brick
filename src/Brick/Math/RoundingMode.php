<?php

namespace Brick\Math;

/**
 * Specifies a rounding behavior for numerical operations capable of discarding precision.
 *
 * Each rounding mode indicates how the least significant returned digit of a rounded result
 * is to be calculated. If fewer digits are returned than the digits needed to represent the
 * exact numerical result, the discarded digits will be referred to as the discarded fraction
 * regardless the digits' contribution to the value of the number. In other words, considered
 * as a numerical value, the discarded fraction could have an absolute value greater than one.
 */
final class RoundingMode
{
    /**
     * Rounds away from zero.
     *
     * Always increments the digit prior to a nonzero discarded fraction.
     * Note that this rounding mode never decreases the magnitude of the calculated value.
     */
    const UP = 0;

    /**
     * Rounds towards zero.
     *
     * Never increments the digit prior to a discarded fraction (i.e., truncates).
     * Note that this rounding mode never increases the magnitude of the calculated value.
     */
    const DOWN = 1;

    /**
     * Rounds towards positive infinity.
     *
     * If the result is positive, behaves as for ROUND_UP; if negative, behaves as for ROUND_DOWN.
     * Note that this rounding mode never decreases the calculated value.
     */
    const CEILING = 2;

    /**
     * Rounds towards negative infinity.
     *
     * If the result is positive, behave as for ROUND_DOWN; if negative, behave as for ROUND_UP.
     * Note that this rounding mode never increases the calculated value.
     */
    const FLOOR = 3;

    /**
     * Rounds towards "nearest neighbor" unless both neighbors are equidistant, in which case round up.
     *
     * Behaves as for ROUND_UP if the discarded fraction is >= 0.5; otherwise, behaves as for ROUND_DOWN.
     * Note that this is the rounding mode commonly taught at school.
     */
    const HALF_UP = 4;

    /**
     * Rounds towards "nearest neighbor" unless both neighbors are equidistant, in which case round down.
     *
     * Behaves as for ROUND_UP if the discarded fraction is > 0.5; otherwise, behaves as for ROUND_DOWN.
     */
    const HALF_DOWN = 5;

    /**
     * Rounds towards the "nearest neighbor" unless both neighbors are equidistant, in which case rounds towards the even neighbor.
     *
     * Behaves as for ROUND_HALF_UP if the digit to the left of the discarded fraction is odd;
     * behaves as for ROUND_HALF_DOWN if it's even.
     *
     * Note that this is the rounding mode that statistically minimizes
     * cumulative error when applied repeatedly over a sequence of calculations.
     * It is sometimes known as "Banker's rounding", and is chiefly used in the USA.
     * This rounding mode is analogous to the rounding policy used for float and double arithmetic.
     */
    const HALF_EVEN = 6;

    /**
     * Asserts that the requested operation has an exact result, hence no rounding is necessary.
     *
     * If this rounding mode is specified on an operation that yields an inexact result,
     * an ArithmeticException is thrown.
     */
    const UNNECESSARY = 7;
}
