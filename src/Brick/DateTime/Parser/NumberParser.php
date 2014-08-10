<?php

namespace Brick\DateTime\Parser;

/**
 * Parses a numeric string.
 */
class NumberParser extends ContextParser
{
    /**
     * The name of the date-time field being parsed.
     *
     * @var string
     */
    private $fieldName;

    /**
     * The min digits length.
     *
     * @var integer
     */
    private $minLength;

    /**
     * The max digits length.
     *
     * @var integer
     */
    private $maxLength;

    /**
     * Whether to allow a minus sign in front of the digits.
     *
     * @var boolean
     */
    private $allowNegative;

    /**
     * Whether to pad the string up to the max length with zeros to the right before converting to an integer.
     *
     * @var boolean
     */
    private $padZerosRight;

    /**
     * Class constructor.
     *
     * @param string  $fieldName     The name of the field being parsed.
     * @param integer $minLength     The min digits length.
     * @param integer $maxLength     The max digits length.
     * @param boolean $allowNegative Whether to allow a minus sign in front of the digits.
     * @param boolean $padZerosRight Whether to pad with zeros to the right up to max length.
     */
    public function __construct($fieldName, $minLength, $maxLength, $allowNegative = false, $padZerosRight = false)
    {
        $this->fieldName     = $fieldName;
        $this->minLength     = $minLength;
        $this->maxLength     = $maxLength;
        $this->allowNegative = $allowNegative;
        $this->padZerosRight = $padZerosRight;
    }

    /**
     * {@inheritdoc}
     */
    public function parseInto(DateTimeParseContext $context)
    {
        if ($this->allowNegative) {
            $sign = $context->getNextCharsMatching('\-');
        } else {
            $sign = '';
        }

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

        if ($this->padZerosRight) {
            $digits = str_pad($digits, $this->maxLength, '0', STR_PAD_RIGHT);
        }

        $context->setParsedField($this->fieldName, (int) ($sign . $digits));

        return true;
    }
}
