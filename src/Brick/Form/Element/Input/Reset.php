<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a reset input element.
 */
class Reset extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'reset';
    }
}
