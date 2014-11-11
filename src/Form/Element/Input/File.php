<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Attribute\MultipleAttribute;
use Brick\Form\Element\Input;
use Brick\Form\Attribute\RequiredAttribute;

/**
 * Represents a file input element.
 */
class File extends Input
{
    // @todo accept attribute
    use MultipleAttribute;
    use RequiredAttribute;

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'file';
    }
}
