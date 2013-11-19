<?php

namespace Brick\Form\Element;

use Brick\Form\Element;
use Brick\Html\SelfClosingTag;

/**
 * Represents an input element.
 */
abstract class Input extends Element
{
    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->tag = new SelfClosingTag('input');
        $this->tag->setAttribute('type', $this->getType());
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->tag->setAttribute('value', $value);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->tag->getAttribute('value');
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->getId(); // @todo find a better way to have it called once before render()
        return $this->tag->render();
    }

    /**
     * Returns the type of this input.
     *
     * @return string
     */
    abstract protected function getType();
}
