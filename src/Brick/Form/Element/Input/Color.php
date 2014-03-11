<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\AutocompleteAttribute;
use Brick\Form\Attribute\ListAttribute;
use Brick\Form\Attribute\PatternAttribute;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a color input element.
 */
class Color extends Input
{
    use AutocompleteAttribute;
    use ListAttribute;
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'color';
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }
}
