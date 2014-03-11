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
    protected $tag;

    /**
     * @var string
     */
    protected $content;

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
     * @return bool
     */
    public function isSelected()
    {
        return $this->tag->hasAttribute('selected');
    }

    /**
     * @param bool $selected Whether to select (true) or unselect (false) this option.
     *
     * @return \Brick\Form\Element\Select\Option\Option
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
