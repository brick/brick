<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates an email address.
 *
 * This validator is *not* RFC 5322 compliant, but instead matches the W3C recommendation
 * for email inputs in HTML5, stating:
 *
 * > This requirement is a willful violation of RFC 5322, which defines a syntax for e-mail addresses
 * > that is simultaneously too strict (before the "@" character), too vague (after the "@" character),
 * > and too lax (allowing comments, whitespace characters, and quoted strings in manners unfamiliar
 * > to most users) to be of practical use here.
 *
 * @see http://www.w3.org/TR/html5/forms.html#states-of-the-type-attribute
 */
class EmailValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.email.invalid' => 'Invalid email address.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        $regexp = '/^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/';

        if (preg_match($regexp, $value) == 0) {
            $this->addFailureMessage('validator.email.invalid');
        }
    }
}
