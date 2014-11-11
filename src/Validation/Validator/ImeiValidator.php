<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;
use Brick\Checksum\Luhn;

/**
 * Validates the IMEI number of a mobile device.
 */
class ImeiValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.imei.invalid' => 'Invalid IMEI number.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        if (! Luhn::isValid($value) || strlen($value) != 15) {
            $this->addFailureMessage('validator.imei.invalid');
        }
    }
}
