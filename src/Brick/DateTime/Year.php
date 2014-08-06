<?php

namespace Brick\DateTime;

use Brick\DateTime\Utility\Cast;

/**
 * Represents a year.
 */
class Year
{
    const MIN_YEAR = LocalDate::MIN_YEAR;
    const MAX_YEAR = LocalDate::MAX_YEAR;

    /**
     * The year being represented.
     *
     * @var integer
     */
    private $value;

    /**
     * Class constructor.
     *
     * @param integer $value The year to represent.
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param integer $value
     *
     * @return Year
     */
    public static function of($value)
    {
        $value = Cast::toInteger($value);

        if ($value < Year::MIN_YEAR || $value > Year::MAX_YEAR) {
            throw new \InvalidArgumentException(sprintf(
                'Year must be in the range %d to %d.',
                self::MIN_YEAR,
                self::MAX_YEAR
            ));
        }

        return new Year($value);
    }

    /**
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks if the year is a leap year, according to the ISO proleptic calendar system rules.
     *
     * This method applies the current rules for leap years across the whole time-line.
     * In general, a year is a leap year if it is divisible by four without
     * remainder. However, years divisible by 100, are not leap years, with
     * the exception of years divisible by 400 which are.
     *
     * The calculation is proleptic - applying the same rules into the far future and far past.
     * This is historically inaccurate, but is correct for the ISO-8601 standard.
     *
     * @return boolean
     */
    public function isLeap()
    {
        return (($this->value & 3) === 0) && (($this->value % 100) !== 0 || ($this->value % 400) === 0);
    }

    /**
     * Returns the length of this year in days.
     *
     * @return integer The length of this year in days, 365 or 366.
     */
    public function getLength()
    {
        return $this->isLeap() ? 366 : 365;
    }

    /**
     * Returns a copy of this year with the specified number of years added.
     *
     * This instance is immutable and unaffected by this method call.
     *
     * @param integer $years The years to add, may be negative.
     *
     * @return Year A Year based on this year with the period added.
     */
    public function plus($years)
    {
        $years = Cast::toInteger($years);

        if ($years === 0) {
            return $this;
        }

        return new Year($this->value + $years);
    }

    /**
     * Returns a copy of this year with the specified number of years subtracted.
     *
     * This instance is immutable and unaffected by this method call.
     *
     * @param integer $years The years to subtract, may be negative.
     *
     * @return Year A Year based on this year with the period subtracted.
     */
    public function minus($years)
    {
        $years = Cast::toInteger($years);

        if ($years === 0) {
            return $this;
        }

        return new Year($this->value - $years);
    }
}
