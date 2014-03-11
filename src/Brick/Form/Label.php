<?php

namespace Brick\Form;

use Brick\Html\ContainerTag;

/**
 * Represents a label that targets a form element.
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
     * Sets the text content of the label.
     *
     * @param string $text
     *
     * @return \Brick\Form\Label
     */
    public function setTextContent($text)
    {
        $this->tag->setTextContent($text);

        return $this;
    }

    /**
     * Sets the HTML content of the label.
     *
     * @param string $html
     *
     * @return \Brick\Form\Label
     */
    public function setHtmlContent($html)
    {
        $this->tag->setHtmlContent($html);

        return $this;
    }

    /**
     * Renders the label.
     *
     * @return string
     */
    public function render()
    {
        return $this->tag->setAttribute('for', $this->element->getId())->render();
    }

    /**
     * Convenience magic method to render the label.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
