<?php

namespace Brick\DateTime\Parser;

use Brick\DateTime\Field\DateTimeField;

/**
 * Parses a time-zone region, such as 'Europe/London'.
 */
class TimeZoneRegionParser extends DateTimeParser
{
    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        $region = $context->getNextCharsMatching('[A-Za-z0-9/_\-]+');

        if ($region !== '') {
            $context->setParsedField(DateTimeField::TIME_ZONE_REGION, $region);

            return true;
        }

        return false;
    }
}
