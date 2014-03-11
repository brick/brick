<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates a regular expression pattern.
 */
class PatternValidator extends AbstractValidator
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.pattern.no-match' => 'The string does not match the given pattern.'
        ];
    }

    /**
     * Class constructor.
     *
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = '/^(?:' . str_replace('/', '\/', $pattern) . ')$/';
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        if (preg_match($this->pattern, $value) == 0) {
            $this->addFailureMessage('validator.pattern.no-match');
        }
    }
}
