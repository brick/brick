<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates an RFC 3339 time.
 */
class TimeValidator implements Validator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();

        if (preg_match('/^([0-9]{2})\:([0-9]{2})(?:\:([0-9]{2}))?$/', $value, $matches) == 0) {
            $result->addFailure('validator.time.invalid');
        } else {
            if ($matches[1] > 23 || $matches[2] > 59 || (isset($matches[3]) && $matches[3] > 59)) {
                $result->addFailure('validator.time.invalid');
            }
        }

        return $result;
    }
}
