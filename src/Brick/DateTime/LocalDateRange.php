<?php

namespace Brick\DateTime;
use Brick\DateTime\Parser\DateTimeParser;
use Brick\DateTime\Parser\DateTimeParseResult;
use Brick\DateTime\Parser\DateTimeParsers;

/**
 * Represents an inclusive range of local dates.
 *
 * LocalDateRange is iteratable: keys are ISO string representation of dates, values are LocalDate objects.
 * LocalDateRange is countable: count() returns the number of dates the range contains.
 */
class LocalDateRange implements \Iterator, \Countable
{
    /**
     * The current date the iterator points to.
     *
     * @var \Brick\DateTime\LocalDate
     */
    private $current;

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
        $this->current = $from;
        $this->from    = $from;
        $this->to      = $to;
    }

    /**
     * @param LocalDate $from
     * @param LocalDate $to
     *
     * @return LocalDateRange
     *
     * @throws DateTimeException If the end date is before the start date.
     */
    public static function of(LocalDate $from, LocalDate $to)
    {
        if ($to->isLessThan($from)) {
            throw new DateTimeException('The end date must not be before the start date.');
        }

        return new LocalDateRange($from, $to);
    }

    /**
     * @param DateTimeParseResult $result
     *
     * @return LocalDateRange
     */
    public static function from(DateTimeParseResult $result)
    {
        return LocalDateRange::of(
            LocalDate::from($result, false),
            LocalDate::from($result, true)
        );
    }

    /**
     * Obtains an instance of `LocalDateRange` from a text string.
     *
     * @todo support partial ends such as `2008-02-15/03-14`
     *
     * @param string              $text   The text to parse, such as `2014-01-01/2014-12-31`.
     * @param DateTimeParser|null $parser An alternative parser.
     *
     * @return LocalDateRange
     *
     * @throws DateTimeException             If either of the dates is not valid.
     * @throws Parser\DateTimeParseException If the text string does not follow the expected format.
     */
    public static function parse($text, DateTimeParser $parser = null)
    {
        if ($parser === null) {
            $parser = DateTimeParsers::isoLocalDateRange();
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
        return $date->isGreaterThanOrEqualTo($this->from) && $date->isLessThanOrEqualTo($this->to);
    }

    /**
     * @return LocalDate
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return (string) $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->current = $this->current->plusDays(1);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->current = $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->current->isLessThanOrEqualTo($this->to);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->to->toInteger() - $this->from->toInteger() + 1;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->from . '/' . $this->to;
    }
}
