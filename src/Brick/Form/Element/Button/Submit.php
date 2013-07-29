<?php

namespace Brick\Form\Element\Button;

use Brick\Form\Element\Button;

/**
 * Represents a submit button element.
 */
class Submit extends Button
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'submit';
    }
}
