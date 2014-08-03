<?php

namespace Brick\DateTime\Parser;

/**
 * Parses a numeric string.
 *
 * @todo sign support
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
    private $fieldName;

    /**
     * @var integer
     */
    private $minLength;

    /**
     * @var integer
     */
    private $maxLength;

    /**
     * Whether to pad the string up to the max length with zeros to the right before converting to an integer.
     *
     * @var boolean
     */
    private $padRight;

    /**
     * @var integer
     */
    private $signStyle;

    /**
     * Class constructor.
     *
     * @param string  $fieldName
     * @param integer $minLength
     * @param integer $maxLength
     * @param boolean $padRight
     * @param integer $signStyle
     */
    public function __construct($fieldName, $minLength, $maxLength, $padRight = false, $signStyle = NumberParser::SIGN_NOT_NEGATIVE)
    {
        $this->fieldName = $fieldName;
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->padRight  = $padRight;
        $this->signStyle = $signStyle;
    }

    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        $digits = $context->getNextDigits();

        if ($digits === '') {
            return false;
        }

        $width = strlen($digits);

        if ($width < $this->minLength || $width > $this->maxLength) {
            throw DateTimeParseException::invalidNumberLength(
                $this->fieldName,
                $this->minLength,
                $this->maxLength,
                $width
            );
        }

        if ($this->padRight) {
            $digits = str_pad($digits, $this->maxLength, '0', STR_PAD_RIGHT);
        }

        $context->setParsedField($this->fieldName, (int) $digits);

        return true;
    }
}
