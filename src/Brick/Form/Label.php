<?php

namespace Brick\Form;

use Brick\Html\ContainerTag;

/**
 * Represents a <label> tag that targets a form element.
 */
class Label
{
    /**
     * @var \Brick\Form\Element
     */
    private $element;

    /**
     * @var \Brick\Html\ContainerTag
     */
    private $tag;

    /**
     * Class constructor.
     *
     * @param Element $element
     */
    public function __construct(Element $element)
    {
        $this->element = $element;
        $this->tag = new ContainerTag('label');
    }

    /**
     * @param string $text
     * @return Label
     */
    public function setTextContent($text)
    {
        $this->tag->setTextContent($text);

        return $this;
    }

    /**
     * @param string $html
     * @return Label
     */
    public function setHtmlContent($html)
    {
        $this->tag->setHtmlContent($html);

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->tag->setAttribute('for', $this->element->getId());

        return $this->tag->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
