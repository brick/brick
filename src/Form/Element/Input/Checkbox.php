<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Attribute\CheckedAttribute;
use Brick\Form\Attribute\RequiredAttribute;
use Brick\Form\Attribute\ValueAttribute;
use Brick\Form\Element\Input;

/**
 * Represents a checkbox input element.
 */
class Checkbox extends Input
{
    use CheckedAttribute;
    use RequiredAttribute;
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'checkbox';
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setChecked($value === $this->getValue());
    }
}
