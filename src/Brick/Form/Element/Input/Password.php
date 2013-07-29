<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a password input element.
 */
class Password extends Text
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'password';
    }
}
