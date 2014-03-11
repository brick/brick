<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Attribute\MultipleAttribute;
use Brick\Form\Element\Input;
use Brick\Form\Attribute\AutocompleteAttribute;
use Brick\Form\Attribute\ListAttribute;
use Brick\Form\Attribute\MaxLengthAttribute;
use Brick\Form\Attribute\PatternAttribute;
use Brick\Form\Attribute\PlaceholderAttribute;
use Brick\Form\Attribute\ReadOnlyAttribute;
use Brick\Form\Attribute\RequiredAttribute;
use Brick\Form\Attribute\SizeAttribute;
use Brick\Form\Attribute\ValueAttribute;
use Brick\Validation\Validator\EmailValidator;

/**
 * Represents an email input element.
 */
class Email extends Input
{
    use AutocompleteAttribute;
    use ListAttribute;
    use MaxLengthAttribute;
    use MultipleAttribute;
    use PatternAttribute;
    use PlaceholderAttribute;
    use ReadOnlyAttribute;
    use RequiredAttribute;
    use SizeAttribute;
    use ValueAttribute;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->addValidator(new EmailValidator());
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }
}
