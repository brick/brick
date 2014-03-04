<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Validation\Validator\TimeValidator;

/**
 * Represents a time input element.
 */
class Time extends Text
{
    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();

        $this->addValidator(new TimeValidator());
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'time';
    }
}
