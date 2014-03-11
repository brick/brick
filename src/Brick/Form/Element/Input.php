<?php

namespace Brick\Form\Element;

use Brick\Form\Element;
use Brick\Html\SelfClosingTag;

/**
 * Represents an input element.
 */
abstract class Input extends Element
{
    // @todo autofocus, disabled, form attributes

    /**
     * @var \Brick\Html\SelfClosingTag|null
     */
    private $tag = null;

    /**
     * {@inheritdoc}
     */
    protected function getTag()
    {
        if ($this->tag === null) {
            $this->tag = new SelfClosingTag('input', [
                'type' => $this->getType()
            ]);
        }

        return $this->tag;
    }

    /**
     * Returns the type of this input.
     *
     * @return string
     */
    abstract protected function getType();
}
