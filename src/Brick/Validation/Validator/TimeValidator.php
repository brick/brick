<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates an RFC 3339 time.
 */
class TimeValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.time.invalid-format' => 'The time format is not valid.',
            'validator.time.invalid-time'   => 'The time is not a valid time.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        if (preg_match('/^([0-9]{2})\:([0-9]{2})(?:\:([0-9]{2}))?$/', $value, $matches) == 0) {
            $this->addFailureMessage('validator.time.invalid-format');
        } else {
            if ($matches[1] > 23 || $matches[2] > 59 || (isset($matches[3]) && $matches[3] > 59)) {
                $this->addFailureMessage('validator.time.invalid-time');
            }
        }
    }
}
