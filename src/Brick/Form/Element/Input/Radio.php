<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a radio button input element.
 */
class Radio extends Checkbox
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'radio';
    }
}
