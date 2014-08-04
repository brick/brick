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
            'Parse error in string "%s" before position %d.',
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

    /**
     * @param string  $fieldName
     * @param integer $minLength
     * @param integer $maxLength
     * @param integer $actualLength
     *
     * @return DateTimeParseException
     */
    public static function invalidNumberLength($fieldName, $minLength, $maxLength, $actualLength)
    {
        $message = 'Expected %d to %d digits for field %s, got %d.';

        return new self(sprintf($message, $minLength, $maxLength, $fieldName, $actualLength));
    }
}
