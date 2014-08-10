<?php

namespace Brick\DateTime\Parser;

/**
 * Interface that all date-time parsers must implement.
 */
interface DateTimeParser
{
    /**
     * @param string $text The text to parse.
     *
     * @return DateTimeParseResult The parse result, containing the parsed fields.
     *
     * @throws DateTimeParseException If the given text could not be parsed.
     */
    public function parse($text);
}
