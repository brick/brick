<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Validation\Validator\EmailValidator;

/**
 * Represents an email input element.
 */
class Email extends Text
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->addValidator(new EmailValidator());
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'email';
    }
}
