<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates that a string does not contain forbidden characters.
 */
class ForbiddenCharsValidator extends AbstractValidator
{
    /**
     * @var string
     */
    private $forbiddenChars;

    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.forbidden-chars' => 'Invalid characters in the string.'
        ];
    }

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
    protected function validate($value)
    {
        $regexp = '/[' . preg_quote($this->forbiddenChars, '/') . ']/';

        if (preg_match($regexp, $value) != 0) {
            $this->addFailureMessage('validator.forbidden-chars');
        }
    }
}
