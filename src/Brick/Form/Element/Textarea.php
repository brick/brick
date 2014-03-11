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
     * @var \Brick\Html\ContainerTag|null
     */
    private $tag = null;

    /**
     * @var string
     */
    private $text = '';

    /**
     * {@inheritdoc}
     */
    protected function getTag()
    {
        if ($this->tag === null) {
            $this->tag = new ContainerTag('textarea');
        }

        return $this->tag;
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->text = $value;

        return $this;
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
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    protected function onBeforeRender()
    {
        $this->getTag()->setTextContent($this->text);
    }
}
