<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

use Brick\Checksum\Luhn;

/**
 * Validates the ICCID number of a SIM card.
 */
class IccidValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();

        if (! Luhn::isValid($value) || strlen($value) != 19 && strlen($value) != 20) {
            $result->addFailure('validator.iccid.invalid');
        }

        return $result;
    }
}
