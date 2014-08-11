<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\DateTimeException;

/**
 * Exception thrown when an error occurs during parsing.
 */
class DateTimeParseException extends DateTimeException
{
    /**
     * @param DateTimeParseContext $context The parse context.
     *
     * @return DateTimeParseException
     */
    public static function parseError(DateTimeParseContext $context)
    {
        $text = $context->getText();
        $position = $context->getPosition();

        return new self(sprintf(
            'Parse error in string "%s" at position %d.',
            $text,
            $position
        ));
    }

    /**
     * @param DateTimeParseContext $context The parse context.
     *
     * @return DateTimeParseException
     */
    public static function unexpectedContent(DateTimeParseContext $context)
    {
        $text = $context->getText();
        $position = $context->getPosition();
        $extra = substr($text, $position);

        return new self(sprintf(
            'Unexpected content "%s" at end of string "%s" at position %d.',
            $extra,
            $text,
            $position
        ));
    }

    /**
     * @param string $textToParse
     *
     * @return DateTimeParseException
     */
    public static function invalidDuration($textToParse)
    {
        return new self('Duration could not be parsed: ' . $textToParse);
    }
}
