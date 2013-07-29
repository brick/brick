<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a telephone number input element.
 */
class Tel extends Text
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'tel';
    }
}
