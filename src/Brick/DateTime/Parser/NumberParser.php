<?php

namespace Brick\DateTime\Parser;

use Brick\Math\Math;

/**
 * Parses a numeric date-time field with optional padding.
 */
class NumberParser extends DateTimeParser
{
    /**
     * Sign only if the value is negative.
     */
    const SIGN_NORMAL = 0;

    /**
     * Always a sign, where zero will output `+`.
     */
    const SIGN_ALWAYS = 1;

    /**
     * Never a sign, only the absolute value.
     */
    const SIGN_NEVER = 2;

    /**
     * No negative values.
     */
    const SIGN_NOT_NEGATIVE = 3;

    /**
     * Sign only if the value exceeds the pad width.
     */
    const SIGN_EXCEEDS_PAD = 4;

    /**
     * @var string
     */
    private $field;

    /**
     * @var int
     */
    private $minWidth;

    /**
     * @var int
     */
    private $maxWidth;

    /**
     * @var int
     */
    private $signStyle;

    /**
     * Class constructor.
     *
     * @param string $field
     * @param int $minWidth
     * @param int $maxWidth
     * @param int $signStyle
     */
    public function __construct($field, $minWidth, $maxWidth = 0, $signStyle = NumberParser::SIGN_NOT_NEGATIVE)
    {
        $this->field = $field;
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
        $this->signStyle = $signStyle;
    }

    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        $digits = $context->getNextDigits();

        if ($digits == '') {
            return false;
        }

        $context->setParsedField($this->field, (int) $digits);

        return true;
    }
}
