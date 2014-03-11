<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a hidden input element.
 */
class Hidden extends Input
{
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'hidden';
    }
}
