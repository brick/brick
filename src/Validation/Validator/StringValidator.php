<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates a UTF-8 string.
 */
class StringValidator extends AbstractValidator
{
    /**
     * @var integer
     */
    private $minLength;

    /**
     * @var integer
     */
    private $maxLength;

    /**
     * @param int $minLength The minimum string length, or zero for no minimum.
     * @param int $maxLength The maximum string length, or zero for no maximum.
     */
    public function __construct($minLength = 0, $maxLength = 0)
    {
        $this->minLength = (int) $minLength;
        $this->maxLength = (int) $maxLength;
    }

    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.string.encoding'  => 'The string is not valid UTF-8.',
            'validator.string.too-short' => 'The string is too short.',
            'validator.string.too-long'  => 'The string is too long.',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        if (! mb_check_encoding($value, 'UTF-8')) {
            $this->addFailureMessage('validator.string.encoding');
            return;
        }

        $length = mb_strlen($value, 'UTF-8');

        if ($this->minLength && $length < $this->minLength) {
            $this->addFailureMessage('validator.string.too-short');
        } elseif ($this->maxLength && $length > $this->maxLength) {
            $this->addFailureMessage('validator.string.too-long');
        }
    }
}
