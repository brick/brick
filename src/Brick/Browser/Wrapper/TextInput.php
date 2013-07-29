<?php

namespace Brick\Browser\Wrapper;

/**
 * A text input.
 */
class TextInput extends TextControl
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->element->getAttribute('value');
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($text)
    {
        $this->element->setAttribute('value', $text);
    }
}
