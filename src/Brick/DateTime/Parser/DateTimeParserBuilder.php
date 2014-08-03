<?php

namespace Brick\DateTime\Parser;

/**
 * Builder to create date-time parsers.
 */
class DateTimeParserBuilder
{
    /**
     * The list of parsers that will be used.
     *
     * @var DateTimeParser[]
     */
    private $parsers = [];

    /**
     * The currently active builder, used by the outermost builder.
     *
     * @var DateTimeParserBuilder
     */
    private $active;

    /**
     * The parent builder, null for the outermost builder.
     *
     * @var DateTimeParserBuilder|null
     */
    private $parent;

    /**
     * Private constructor. Use create() to create an instance.
     *
     * @param DateTimeParserBuilder|null $parent
     */
    private function __construct(DateTimeParserBuilder $parent = null)
    {
        $this->active = $this;
        $this->parent = $parent;
    }

    /**
     * Creates an instance of `DateTimeParserBuilder`.
     *
     * @return DateTimeParserBuilder
     */
    public static function create()
    {
        return new DateTimeParserBuilder();
    }

    /**
     * Appends a date-time parser.
     *
     * @param DateTimeParser $parser
     *
     * @return DateTimeParserBuilder
     */
    public function append(DateTimeParser $parser)
    {
        $this->active->parsers[] = $parser;

        return $this;
    }

    /**
     * Marks the start of an optional section.
     *
     * @return DateTimeParserBuilder
     */
    public function optionalStart()
    {
        $this->active = new DateTimeParserBuilder($this->active);

        return $this;
    }

    /**
     * Ends an optional section.
     *
     * @return DateTimeParserBuilder
     *
     * @throws DateTimeParseException
     */
    public function optionalEnd()
    {
        if (! $this->active->parent) {
            throw new DateTimeParseException(
                'Cannot call optionalEnd() as there was no previous call to optionalStart()'
            );
        }

        if ($this->active->parsers) {
            $parser = new CompositeParser($this->active->parsers, true);
            $this->active = $this->active->parent;
            $this->append($parser);
        } else {
            $this->active = $this->active->parent;
        }

        return $this;
    }

    /**
     * Completes this builder by creating the DateTimeParser.
     *
     * @return DateTimeParser
     */
    public function toParser()
    {
        while ($this->active->parent) {
            $this->optionalEnd();
        }

        return new CompositeParser($this->parsers, false);
    }
}
