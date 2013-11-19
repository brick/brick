<?php

namespace Brick\Form\Element;

use Brick\Form\Element;
use Brick\Html\ContainerTag;

/**
 * Represents a button element.
 */
abstract class Button extends Element
{
    /**
     * This variable is purposefully redeclared to typehint the correct Tag subclass.
     *
     * @var \Brick\Html\ContainerTag
     */
    protected $tag;

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->tag = new ContainerTag('button');
        $this->tag->setAttribute('type', $this->getType());
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        $this->tag->setAttribute('value', $value);
        return $this;
    }

    /**
     * @eturn string|null
     */
    public function getValue()
    {
        return $this->tag->getAttribute('value');
    }

    /**
     * @return string
     */
    public function openTag()
    {
        return $this->tag->renderOpeningTag();
    }

    /**
     * @return string
     */
    public function closeTag()
    {
        return $this->tag->renderClosingTag();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        throw new \BadMethodCallException('Not supported in button');
    }

    /**
     * Returns the type of this button.
     *
     * @return string
     */
    abstract protected function getType();
}
