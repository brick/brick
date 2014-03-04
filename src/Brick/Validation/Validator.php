<?php

namespace Brick\Validation;

/**
 * Interface that all validators must implement.
 */
interface Validator
{
    /**
     * Validates the given value.
     *
     * @param string $value The value to validate.
     *
     * @return ValidationResult The result of the validation.
     */
    public function validate($value);
}
