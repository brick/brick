<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\CheckedAttribute;
use Brick\Form\Attribute\RequiredAttribute;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a radio button input element.
 */
class Radio extends Input
{
    use CheckedAttribute;
    use RequiredAttribute;
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'radio';
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setChecked($value !== null && $value === $this->getValue());
    }
}
