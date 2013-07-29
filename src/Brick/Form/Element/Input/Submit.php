<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a submit input element.
 */
class Submit extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'submit';
    }
}
