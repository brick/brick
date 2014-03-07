<?php

namespace Brick\Validation\Validator;

use Brick\Validation\Validator;
use Brick\Validation\ValidationResult;

/**
 * Validates a regular expression pattern.
 */
class PatternValidator implements Validator
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * Class constructor.
     *
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $result = new ValidationResult();

        if (preg_match($this->pattern, $value) == 0) {
            $result->addFailure('validator-pattern-does-not-match');
        }

        return $result;
    }
}
