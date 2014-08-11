<?php

namespace Brick\DateTime\Field;

/**
 * The fraction-of-second field.
 *
 * @internal
 */
class FractionOfSecond
{
    /**
     * The field name.
     */
    const NAME = 'fraction-of-second';

    /**
     * The regular expression pattern of the ISO 8601 representation.
     */
    const PATTERN = '[0-9]{1,9}';
}
