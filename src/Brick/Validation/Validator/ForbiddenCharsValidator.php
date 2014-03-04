<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates that a string does not contain forbidden characters.
 */
class ForbiddenCharsValidator implements Validator
{
    /**
     * @var string
     */
    private $forbiddenChars;

    /**
     * Class constructor.
     *
     * @param string $forbiddenChars A string containing all forbidden characters.
     */
    public function __construct($forbiddenChars)
    {
        $this->forbiddenChars = (string) $forbiddenChars;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();
        $regexp = '/[' . preg_quote($this->forbiddenChars, '/') . ']/';

        if (preg_match($regexp, $value) != 0) {
            $result->addFailure('validator.forbidden.chars');
        }

        return $result;
    }
}
