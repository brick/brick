<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

use Brick\Checksum\Luhn;

/**
 * Validates an IMEI number.
 */
class ImeiValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();

        if (! Luhn::isValid($value) || strlen($value) != 15) {
            $result->addFailure('validator.imei.invalid');
        }

        return $result;
    }
}
