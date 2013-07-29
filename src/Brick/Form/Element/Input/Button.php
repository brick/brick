<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a button input element.
 */
class Button extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'button';
    }
}
