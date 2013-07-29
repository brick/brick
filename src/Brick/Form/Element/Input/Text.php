<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a text input element.
 */
class Text extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    protected function populate($value)
    {
        $this->setValue($value);
    }
}
