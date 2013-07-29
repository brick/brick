<?php

namespace Brick\Form\Element\Select;

use Brick\Html\ContainerTag;
use Brick\Form\Element\Renderable;

/**
 * Represents an option group inside a select element.
 */
class OptionGroup implements Renderable
{
    /**
     * The HTML tag used to render this OptionGroup.
     *
     * @var \Brick\Html\ContainerTag
     */
    protected $tag;

    /**
     * The options inside this OptionGroup.
     *
     * @var Option[]
     */
    protected $options = [];

    /**
     * Class constructor.
     *
     * @param string $label The option group label.
     */
    public function __construct($label)
    {
        $this->tag = new ContainerTag('optgroup');
        $this->tag->setAttribute('label', $label);
    }

    /**
     * Adds an option to this OptionGroup.
     *
     * @param string $content The text content of this option.
     * @param string $value   The value of this option.
     * @return OptionGroup
     */
    public function addOption($content, $value)
    {
        $this->options[] = new Option($content, $value);
        return $this;
    }

    /**
     * Adds a batch of options to this OptionGroup.
     *
     * The array format is [value] => content.
     *
     * @param array $options
     * @return OptionGroup
     */
    public function addOptions(array $options)
    {
        foreach ($options as $value => $content) {
            $this->addOption($content, $value);
        }

        return $this;
    }

    /**
     * Returns the options in this OptionGroup.
     *
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $content = '';

        foreach ($this->options as $option) {
            $content .= $option->render();
        }

        return $this->tag->setHtmlContent($content)->render();
    }
}
