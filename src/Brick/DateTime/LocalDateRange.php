<?php

namespace Brick\DateTime;

use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\Parser\DateTimeParser;
use Brick\DateTime\Parser\DateTimeParseResult;
use Brick\DateTime\Parser\DateTimeParsers;
use Brick\DateTime\Parser\IsoParsers;

/**
 * Represents an inclusive range of local dates.
 *
 * LocalDateRange is iteratable: keys are ISO string representation of dates, values are LocalDate objects.
 * LocalDateRange is countable: count() returns the number of dates the range contains.
 */
class LocalDateRange implements \IteratorAggregate, \Countable
{
    /**
     * The from date.
     *
     * @var \Brick\DateTime\LocalDate
     */
    private $from;

    /**
     * The to date.
     *
     * @var \Brick\DateTime\LocalDate
     */
    private $to;

    /**
     * Class constructor.
     *
     * @param LocalDate $from The start date.
     * @param LocalDate $to   The end date, validated as not before the start date.
     */
    private function __construct(LocalDate $from, LocalDate $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    /**
     * Creates an instance of LocalDateRange from two LocalDate instances.
     *
     * @param LocalDate $from The start date.
     * @param LocalDate $to   The end date.
     *
     * @return LocalDateRange
     *
     * @throws DateTimeException If the end date is before the start date.
     */
    public static function of(LocalDate $from, LocalDate $to)
    {
        if ($to->isBefore($from)) {
            throw new DateTimeException('The end date must not be before the start date.');
        }

        return new LocalDateRange($from, $to);
    }

    /**
     * @param DateTimeParseResult $result
     *
     * @return LocalDateRange
     *
     * @throws DateTimeException      If the date range is not valid.
     * @throws DateTimeParseException If required fields are missing from the result.
     */
    public static function from(DateTimeParseResult $result)
    {
        return LocalDateRange::of(
            LocalDate::from($result),
            LocalDate::from($result)
        );
    }

    /**
     * Obtains an instance of `LocalDateRange` from a text string.
     *
     * @todo support partial ends such as `2008-02-15/03-14`
     *
     * @param string              $text   The text to parse, such as `2014-01-01/2014-12-31`.
     * @param DateTimeParser|null $parser The parser to use, defaults to the ISO 8601 parser.
     *
     * @return LocalDateRange
     *
     * @throws DateTimeException      If either of the dates is not valid.
     * @throws DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, DateTimeParser $parser = null)
    {
        if (! $parser) {
            $parser = IsoParsers::localDateRange();
        }

        return LocalDateRange::from($parser->parse($text));
    }

    /**
     * @return LocalDate
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return LocalDate
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Returns whether this DateRange is equal to the given one.
     *
     * @param LocalDateRange $that
     *
     * @return boolean
     */
    public function isEqualTo(LocalDateRange $that)
    {
        return $this->from->isEqualTo($that->from) && $this->to->isEqualTo($that->to);
    }

    /**
     * Returns whether this DateRange contains the given date.
     *
     * @param LocalDate $date
     *
     * @return boolean
     */
    public function contains(LocalDate $date)
    {
        return ! ($date->isBefore($this->from) || $date->isAfter($this->to));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $date = $this->from;

        while (! $date->isAfter($this->to)) {
            yield $date;
            $date = $date->plusDays(1);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->to->toEpochDay() - $this->from->toEpochDay() + 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->from . '/' . $this->to;
    }
}
