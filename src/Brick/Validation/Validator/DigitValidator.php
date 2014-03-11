<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates a string of digits.
 */
class DigitValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.digit.invalid' => 'All characters must be digits.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        if (! ctype_digit($value)) {
            $this->addFailureMessage('validator.digit.invalid');
        }
    }
}
