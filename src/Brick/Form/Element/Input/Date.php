<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Validation\Validator\DateValidator;

/**
 * Represents a date input element.
 */
class Date extends Text
{
    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();

        $this->addValidator(new DateValidator());
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'date';
    }
}
