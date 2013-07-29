<?php

namespace Brick\Form\Element;

use Brick\Form\Element;
use Brick\Html\ContainerTag;

/**
 * Represents a textarea element.
 */
class Textarea extends Element
{
    /**
     * This variable is purposefully redeclared to typehint the correct Tag subclass.
     *
     * @var \Brick\Html\ContainerTag
     */
    protected $tag;

    /**
     * @var string
     */
    protected $text = '';

    /**
     * Class constructor.
     */
    public function init()
    {
        $this->tag = new ContainerTag('textarea');
    }

    /**
     * @param string $value
     * @return Textarea
     */
    public function setValue($value)
    {
        $this->text = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    protected function populate($value)
    {
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->getId(); // @todo find a better way to have it called once before render()
        return $this->tag->setTextContent($this->text)->render();
    }
}
