<?php

namespace Brick\DateTime\Parser;

/**
 * Base class for date-time parsers using the parse context.
 */
abstract class ContextParser implements DateTimeParser
{
    /**
     * @param DateTimeParseContext $context The parsing context.
     *
     * @return boolean True if the parsing was successful, false otherwise.
     */
    abstract public function parseInto(DateTimeParseContext $context);

    /**
     * {@inheritdoc}
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
