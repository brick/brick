<?php

namespace Brick\Form\Element\Select\Option;

use Brick\Html\ContainerTag;

/**
 * Represents an option inside a select element.
 */
class Option extends OptionOrGroup
{
    /**
     * @var \Brick\Html\ContainerTag
     */
    private $tag;

    /**
     * @var string
     */
    private $content;

    /**
     * Class constructor.
     *
     * @param string $content The text content of this option.
     * @param string $value   The value of this option.
     */
    public function __construct($content, $value)
    {
        $this->tag = new ContainerTag('option', [
            'value' => $value
        ]);

        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->tag->getAttribute('value');
    }

    /**
     * @return boolean
     */
    public function isSelected()
    {
        return $this->tag->hasAttribute('selected');
    }

    /**
     * @param boolean $selected Whether to select (true) or unselect (false) this option.
     *
     * @return static
     */
    public function setSelected($selected)
    {
        if ($selected) {
            $this->tag->setAttribute('selected', 'selected');
        } else {
            $this->tag->removeAttribute('selected');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->tag->setTextContent($this->content)->render();
    }
}
