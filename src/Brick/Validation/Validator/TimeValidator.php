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
        if (! is_null($value) && ! is_string($value)) {
            throw new \InvalidArgumentException('Value must be a string or null');
        }

        $result = new ValidationResult();

        if (preg_match('/^([0-9]{2})\:([0-9]{2})(?:\:([0-9]{2}))?$/', $value, $matches) == 0) {
            $result->addFailure('validator.time.invalid');
        } else {
            if ($matches[1] > 24 || $matches[2] > 60 || (isset($matches[3]) && $matches[3] > 60)) {
                $result->addFailure('validator.time.invalid');
            }
        }

        return $result;
    }
}
