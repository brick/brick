<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\DateTimeException;

/**
 * Exception thrown when an error occurs during parsing.
 */
class DateTimeParseException extends DateTimeException
{
    /**
     * @param DateTimeParseContext $context
     *
     * @return DateTimeParseException
     */
    public static function parseError(DateTimeParseContext $context)
    {
        return new self(sprintf(
            'Parse error for string "%s" at position %d',
            $context->getText(),
            $context->getPosition()
        ));
    }

    /**
     * @param DateTimeParseContext $context
     *
     * @return DateTimeParseException
     */
    public static function unexpectedContent(DateTimeParseContext $context)
    {
        return new self(sprintf(
            'Unexpected content at end of string "%" at position %d',
            $context->getText(),
            $context->getPosition()
        ));
    }

    /**
     * @param string      $textToParse The text being parsed.
     * @param string|null $reason      The reason, or null if no reason is available.
     *
     * @return DateTimeParseException
     */
    public static function invalidTimeZoneOffset($textToParse, $reason = null)
    {
        $message = sprintf('Time zone offset "%s" is invalid', $textToParse);

        if ($reason !== null) {
            $message .= ': ' . $reason;
        }

        return new self($message);
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
