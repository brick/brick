<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a button input element.
 */
class Button extends Input
{
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'button';
    }
}
