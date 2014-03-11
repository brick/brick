<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a reset input element.
 */
class Reset extends Input
{
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'reset';
    }
}
