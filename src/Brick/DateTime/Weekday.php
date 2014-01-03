<?php

namespace Brick\DateTime;

/**
 * A day-of-week, such as Tuesday.
 *
 * This class is immutable.
 */
class Weekday
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
     * @var Weekday[]
     */
    private static $values = [];

    /**
     * The ISO-8601 value for the day of the week, from 1 (Monday) to 7 (Sunday).
     *
     * @var integer
     */
    private $value;

    /**
     * Private constructor. Use of() to get a Weekday instance.
     *
     * @param integer $day
     */
    private function __construct($day)
    {
        $this->value = $day;
    }

    /**
     * Returns an instance of Weekday for the given day-of-week value.
     *
     * @param integer $day The day of the week, from 1 (Monday) to 7 (Sunday).
     *
     * @return Weekday The Weekday instance.
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
                self::$values[$day] = new Weekday($day);
            }

            return self::$values[$day];
        }

        throw new \UnexpectedValueException('Day must be an integer in the interval [1-7]');
    }

    /**
     * Returns a Weekday representing Monday.
     *
     * @return Weekday
     */
    public static function monday()
    {
        return Weekday::of(Weekday::MONDAY);
    }

    /**
     * Returns a Weekday representing Tuesday.
     *
     * @return Weekday
     */
    public static function tuesday()
    {
        return Weekday::of(Weekday::TUESDAY);
    }

    /**
     * Returns a Weekday representing Wednesday.
     *
     * @return Weekday
     */
    public static function wednesday()
    {
        return Weekday::of(Weekday::WEDNESDAY);
    }

    /**
     * Returns a Weekday representing Thursday.
     *
     * @return Weekday
     */
    public static function thursday()
    {
        return Weekday::of(Weekday::THURSDAY);
    }

    /**
     * Returns a Weekday representing Friday.
     *
     * @return Weekday
     */
    public static function friday()
    {
        return Weekday::of(Weekday::FRIDAY);
    }

    /**
     * Returns a Weekday representing Saturday.
     *
     * @return Weekday
     */
    public static function saturday()
    {
        return Weekday::of(Weekday::SATURDAY);
    }

    /**
     * Returns a Weekday representing Sunday.
     *
     * @return Weekday
     */
    public static function sunday()
    {
        return Weekday::of(Weekday::SUNDAY);
    }

    /**
     * Returns the current Weekday in the given timezone.
     *
     * @param \Brick\DateTime\TimeZone $timezone
     *
     * @return Weekday
     */
    public static function today(TimeZone $timezone)
    {
        return Weekday::fromDateTime(ZonedDateTime::now($timezone));
    }

    /**
     * Factory method returning a Weekday from a Brick DateTime.
     *
     * @param ZonedDateTime $date
     *
     * @return Weekday
     */
    public static function fromDateTime(ZonedDateTime $date)
    {
        return $date->getDayOfWeek();
    }

    /**
     * Factory method returning a Weekday from a Brick Date.
     *
     * @param LocalDate $date
     *
     * @return Weekday
     */
    public static function fromDate(LocalDate $date)
    {
        $dateTime = new \DateTime(null, new \DateTimeZone('UTC'));
        $date->applyToDateTime($dateTime);

        return Weekday::of($dateTime->format('N'));
    }

    /**
     * Returns the seven days of the week in an array.
     *
     * @param Weekday $first The day to return first. Optional, defaults to Monday.
     *
     * @return Weekday[]
     */
    public static function getWeekdays(Weekday $first = null)
    {
        $weekdays = [];
        $first = $first ?: Weekday::monday();
        $current = $first;

        do {
            $weekdays[] = $current;
            $current = $current->plus(1);
        }
        while (! $current->isEqualTo($first));

        return $weekdays;
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
     * Returns the ISO 8601 value of this Weekday.
     *
     * @return integer The day-of-week, from 1 (Monday) to 7 (Sunday).
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns whether this Weekday equals another Weekday.
     *
     * Even though of() returns the same instance if the same day is requested several times,
     * do *not* use strict object comparison to compare two Weekday instances,
     * as it is possible to get a different instance for the same day using serialization.
     *
     * @param Weekday $other
     *
     * @return boolean
     */
    public function isEqualTo(Weekday $other)
    {
        return $this->value === $other->value;
    }

    /**
     * Returns the Weekday that is the specified number of days after this one.
     *
     * @param integer $days
     *
     * @return Weekday
     */
    public function plus($days)
    {
        return Weekday::of((((($this->value - 1 + $days) % 7) + 7) % 7) + 1);
    }

    /**
     * Returns the Weekday that is the specified number of days before this one.
     *
     * @param integer $days
     *
     * @return Weekday
     */
    public function minus($days)
    {
        return $this->plus(- $days);
    }

    /**
     * Returns whether this Weekday represents Monday.
     *
     * @return boolean
     */
    public function isMonday()
    {
        return $this->value === self::MONDAY;
    }

    /**
     * Returns whether this Weekday represents Tuesday.
     *
     * @return boolean
     */
    public function isTuesday()
    {
        return $this->value === self::TUESDAY;
    }

    /**
     * Returns whether this Weekday represents Wednesday.
     *
     * @return boolean
     */
    public function isWednesday()
    {
        return $this->value === self::WEDNESDAY;
    }

    /**
     * Returns whether this Weekday represents Thursday.
     *
     * @return boolean
     */
    public function isThursday()
    {
        return $this->value === self::THURSDAY;
    }

    /**
     * Returns whether this Weekday represents Friday.
     *
     * @return boolean
     */
    public function isFriday()
    {
        return $this->value === self::FRIDAY;
    }

    /**
     * Returns whether this Weekday represents Saturday.
     *
     * @return boolean
     */
    public function isSaturday()
    {
        return $this->value === self::SATURDAY;
    }

    /**
     * Returns whether this Weekday represents Sunday.
     *
     * @return boolean
     */
    public function isSunday()
    {
        return $this->value === self::SUNDAY;
    }
}
