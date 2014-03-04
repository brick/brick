<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates that a string is not empty, and not null.
 */
class RequiredValidator implements Validator
{
    /**
     * Whether to trim the white spaces around the string before validation.
     *
     * @var bool
     */
    protected $trim;

    /**
     * Class constructor.
     *
     * @param bool $trim
     */
    public function __construct($trim = false)
    {
        $this->trim = $trim;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();

        if ($this->trim) {
            $value = trim($value);
        }

        if ($value == '') {
            $result->addFailure('validator.required');
        }

        return $result;
    }
}
