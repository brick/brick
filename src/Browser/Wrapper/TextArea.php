<?php

namespace Brick\Browser\Wrapper;

/**
 * A text area.
 */
class TextArea extends TextControl
{
    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->element->getDomElement()->nodeValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($text)
    {
        $this->element->getDomElement()->nodeValue = $text;
    }
}
