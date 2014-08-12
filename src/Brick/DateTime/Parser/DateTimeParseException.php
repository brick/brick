<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\DateTimeException;

/**
 * Exception thrown when an error occurs during parsing.
 */
class DateTimeParseException extends DateTimeException
{
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
