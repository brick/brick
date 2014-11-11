<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;
use Brick\Checksum\Luhn;

/**
 * Validates a SIM card number (ICCID).
 */
class SimValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.sim.invalid' => 'Invalid SIM card number.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        if (! Luhn::isValid($value) || strlen($value) != 19 && strlen($value) != 20) {
            $this->addFailureMessage('validator.sim.invalid');
        }
    }
}
