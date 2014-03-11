<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Attribute\MinMaxStepAttributes;
use Brick\Form\Element\Input;
use Brick\Form\Attribute\AutocompleteAttribute;
use Brick\Form\Attribute\ListAttribute;
use Brick\Form\Attribute\ReadOnlyAttribute;
use Brick\Form\Attribute\RequiredAttribute;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a month input element.
 */
class Month extends Input
{
    use AutocompleteAttribute;
    use ListAttribute;
    use MinMaxStepAttributes;
    use ReadOnlyAttribute;
    use RequiredAttribute;
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'month';
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }
}
