<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a hidden input element.
 */
class Hidden extends Text
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'hidden';
    }
}
