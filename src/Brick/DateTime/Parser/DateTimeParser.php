<?php

namespace Brick\DateTime\Parser;

/**
 * Base class that date-time parsers must extend.
 */
abstract class DateTimeParser
{
    /**
     * @param DateTimeParseContext $context The parsing context.
     *
     * @return boolean Whether the parsing was successful.
     */
    abstract public function parseInto(DateTimeParseContext $context);

    /**
     * @param string $text
     *
     * @return DateTimeParseResult
     *
     * @throws DateTimeParseException
     */
    public function parse($text)
    {
        $context = DateTimeParseContext::create($text);

        if (! $this->parseInto($context)) {
            throw DateTimeParseException::parseError($context);
        }

        return $context->toParseResult();
    }
}
