<?php

namespace Brick\Form\Element\Button;

use \Brick\Form\Element\Button;

/**
 * Represents a reset button element.
 */
class Reset extends Button
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'reset';
    }
}
