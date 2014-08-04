<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\Field\DateTimeField;

/**
 * Parses a time-zone offset, such as 'Z' or '+01:00'.
 */
class TimeZoneOffsetParser extends DateTimeParser
{
    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        $char = $context->getNextChars(1);

        if ($char === 'Z') {
            $context->setParsedField(DateTimeField::TIME_ZONE_OFFSET_SIGN, '+');
            $context->setParsedField(DateTimeField::TIME_ZONE_OFFSET_HOUR, 0);
            $context->setParsedField(DateTimeField::TIME_ZONE_OFFSET_MINUTE, 0);

            return true;
        }

        if ($char === '+' || $char === '-') {
            $context->setParsedField(DateTimeField::TIME_ZONE_OFFSET_SIGN, $char);

            $parser = DateTimeParserBuilder::create()
                ->append(new NumberParser(DateTimeField::TIME_ZONE_OFFSET_HOUR, 2, 2))
                ->append(new StringLiteralParser(':'))
                ->append(new NumberParser(DateTimeField::TIME_ZONE_OFFSET_MINUTE, 2, 2))
                ->optionalStart()
                ->append(new StringLiteralParser(':'))
                ->append(new NumberParser(DateTimeField::TIME_ZONE_OFFSET_SECOND, 2, 2))
                ->toParser();

            return $parser->parseInto($context);
        }

        return false;
    }
}
