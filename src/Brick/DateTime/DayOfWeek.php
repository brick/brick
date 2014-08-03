<?php

namespace Brick\DateTime;

/**
 * A day-of-week, such as Tuesday.
 *
 * This class is immutable.
 */
class DayOfWeek
{
    const MONDAY    = 1;
    const TUESDAY   = 2;
    const WEDNESDAY = 3;
    const THURSDAY  = 4;
    const FRIDAY    = 5;
    const SATURDAY  = 6;
    const SUNDAY    = 7;

    /**
     * Cache of all the days of week.
     *
     * @var DayOfWeek[]
     */
    private static $values = [];

    /**
     * The ISO-8601 value for the day of the week, from 1 (Monday) to 7 (Sunday).
     *
     * @var integer
     */
    private $value;

    /**
     * Private constructor. Use of() to get a DayOfWeek instance.
     *
     * @param integer $day
     */
    private function __construct($day)
    {
        $this->value = $day;
    }

    /**
     * Returns an instance of DayOfWeek for the given day-of-week value.
     *
     * @param integer $day The day of the week, from 1 (Monday) to 7 (Sunday).
     *
     * @return DayOfWeek The DayOfWeek instance.
     *
     * @throws \UnexpectedValueException
     */
    public static function of($day)
    {
        $day = filter_var($day, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => self::MONDAY,
                'max_range' => self::SUNDAY
            ]
        ]);

        if (is_int($day)) {
            if (! isset(self::$values[$day])) {
                self::$values[$day] = new DayOfWeek($day);
            }

            return self::$values[$day];
        }

        throw new \UnexpectedValueException('Day must be an integer in the interval [1-7]');
    }

    /**
     * Returns a DayOfWeek representing Monday.
     *
     * @return DayOfWeek
     */
    public static function monday()
    {
        return DayOfWeek::of(DayOfWeek::MONDAY);
    }

    /**
     * Returns a DayOfWeek representing Tuesday.
     *
     * @return DayOfWeek
     */
    public static function tuesday()
    {
        return DayOfWeek::of(DayOfWeek::TUESDAY);
    }

    /**
     * Returns a DayOfWeek representing Wednesday.
     *
     * @return DayOfWeek
     */
    public static function wednesday()
    {
        return DayOfWeek::of(DayOfWeek::WEDNESDAY);
    }

    /**
     * Returns a DayOfWeek representing Thursday.
     *
     * @return DayOfWeek
     */
    public static function thursday()
    {
        return DayOfWeek::of(DayOfWeek::THURSDAY);
    }

    /**
     * Returns a DayOfWeek representing Friday.
     *
     * @return DayOfWeek
     */
    public static function friday()
    {
        return DayOfWeek::of(DayOfWeek::FRIDAY);
    }

    /**
     * Returns a DayOfWeek representing Saturday.
     *
     * @return DayOfWeek
     */
    public static function saturday()
    {
        return DayOfWeek::of(DayOfWeek::SATURDAY);
    }

    /**
     * Returns a DayOfWeek representing Sunday.
     *
     * @return DayOfWeek
     */
    public static function sunday()
    {
        return DayOfWeek::of(DayOfWeek::SUNDAY);
    }

    /**
     * Returns the current DayOfWeek in the given timezone.
     *
     * @param \Brick\DateTime\TimeZone $timezone
     *
     * @return DayOfWeek
     */
    public static function now(TimeZone $timezone)
    {
        return LocalDate::now($timezone)->getDayOfWeek();
    }

    /**
     * Returns the DayOfWeek of the given ZonedDateTime.
     *
     * @param ZonedDateTime $date
     *
     * @return DayOfWeek
     */
    public static function fromDateTime(ZonedDateTime $date)
    {
        return $date->getDayOfWeek();
    }

    /**
     * Returns the DayOfWeek of the given LocalDate.
     *
     * @param LocalDate $date
     *
     * @return DayOfWeek
     */
    public static function fromDate(LocalDate $date)
    {
        $dateTime = new \DateTime(null, new \DateTimeZone('UTC'));
        $date->applyToDateTime($dateTime);

        return DayOfWeek::of($dateTime->format('N'));
    }

    /**
     * Returns the seven days of the week in an array.
     *
     * @param DayOfWeek $first The day to return first. Optional, defaults to Monday.
     *
     * @return DayOfWeek[]
     */
    public static function getAll(DayOfWeek $first = null)
    {
        $days = [];
        $first = $first ?: DayOfWeek::monday();
        $current = $first;

        do {
            $days[] = $current;
            $current = $current->plus(1);
        }
        while (! $current->isEqualTo($first));

        return $days;
    }

    /**
     * Returns the English name of this day of the week.
     *
     * @return string
     */
    public function getName()
    {
        return jddayofweek($this->value - 1, CAL_DOW_SHORT);
    }

    /**
     * Returns the ISO 8601 value of this DayOfWeek.
     *
     * @return integer The day-of-week, from 1 (Monday) to 7 (Sunday).
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns whether this DayOfWeek equals another DayOfWeek.
     *
     * Even though of() returns the same instance if the same day is requested several times,
     * do *not* use strict object comparison to compare two DayOfWeek instances,
     * as it is possible to get a different instance for the same day using serialization.
     *
     * @param DayOfWeek $that
     *
     * @return boolean
     */
    public function isEqualTo(DayOfWeek $that)
    {
        return $this->value === $that->value;
    }

    /**
     * Returns the DayOfWeek that is the specified number of days after this one.
     *
     * @param integer $days
     *
     * @return DayOfWeek
     */
    public function plus($days)
    {
        return DayOfWeek::of((((($this->value - 1 + $days) % 7) + 7) % 7) + 1);
    }

    /**
     * Returns the DayOfWeek that is the specified number of days before this one.
     *
     * @param integer $days
     *
     * @return DayOfWeek
     */
    public function minus($days)
    {
        return $this->plus(- $days);
    }

    /**
     * Returns whether this DayOfWeek represents Monday.
     *
     * @return boolean
     */
    public function isMonday()
    {
        return $this->value === self::MONDAY;
    }

    /**
     * Returns whether this DayOfWeek represents Tuesday.
     *
     * @return boolean
     */
    public function isTuesday()
    {
        return $this->value === self::TUESDAY;
    }

    /**
     * Returns whether this DayOfWeek represents Wednesday.
     *
     * @return boolean
     */
    public function isWednesday()
    {
        return $this->value === self::WEDNESDAY;
    }

    /**
     * Returns whether this DayOfWeek represents Thursday.
     *
     * @return boolean
     */
    public function isThursday()
    {
        return $this->value === self::THURSDAY;
    }

    /**
     * Returns whether this DayOfWeek represents Friday.
     *
     * @return boolean
     */
    public function isFriday()
    {
        return $this->value === self::FRIDAY;
    }

    /**
     * Returns whether this DayOfWeek represents Saturday.
     *
     * @return boolean
     */
    public function isSaturday()
    {
        return $this->value === self::SATURDAY;
    }

    /**
     * Returns whether this DayOfWeek represents Sunday.
     *
     * @return boolean
     */
    public function isSunday()
    {
        return $this->value === self::SUNDAY;
    }
}
