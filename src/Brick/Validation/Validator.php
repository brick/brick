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
     * @param mixed $value The value to validate.
     *
     * @return ValidationResult The result of the validation.
     *
     * @throws \InvalidArgumentException If the value is of an unexpected type.
     */
    public function validate($value);
}
