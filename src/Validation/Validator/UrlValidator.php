<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates a URL.
 *
 * The validation is rudimentary, and basically checks that the URL contains a scheme and a host.
 */
class UrlValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.url.invalid' => 'Invalid URL.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        $parts = parse_url($value);

        if ($parts === false) {
            $this->addFailureMessage('validator.url.invalid');
        }

        if (! isset($parts['scheme'])) {
            $this->addFailureMessage('validator.url.invalid');
        }

        if (! isset($parts['host'])) {
            $this->addFailureMessage('validator.url.invalid');
        }
    }
}
