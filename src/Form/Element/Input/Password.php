<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;
use Brick\Form\Attribute\AutocompleteAttribute;
use Brick\Form\Attribute\MaxLengthAttribute;
use Brick\Form\Attribute\PatternAttribute;
use Brick\Form\Attribute\PlaceholderAttribute;
use Brick\Form\Attribute\ReadOnlyAttribute;
use Brick\Form\Attribute\RequiredAttribute;
use Brick\Form\Attribute\SizeAttribute;
use Brick\Form\Attribute\ValueAttribute;

/**
 * Represents a password input element.
 */
class Password extends Input
{
    use AutocompleteAttribute;
    use MaxLengthAttribute;
    use PatternAttribute;
    use PlaceholderAttribute;
    use ReadOnlyAttribute;
    use RequiredAttribute;
    use SizeAttribute;
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'password';
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }
}
