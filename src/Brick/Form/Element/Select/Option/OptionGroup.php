<?php

namespace Brick\Form\Element\Select\Option;

use Brick\Form\Element\Select\Option\Option;
use Brick\Form\Element\Select\Option\OptionOrGroup;
use Brick\Html\ContainerTag;

/**
 * Represents an option group inside a select element.
 */
class OptionGroup extends OptionOrGroup
{
    /**
     * The HTML tag used to render this OptionGroup.
     *
     * @var \Brick\Html\ContainerTag
     */
    private $tag;

    /**
     * The options inside this OptionGroup.
     *
     * @var \Brick\Form\Element\Select\Option\Option[]
     */
    private $options = [];

    /**
     * Class constructor.
     *
     * @param string $label The option group label.
     */
    public function __construct($label)
    {
        $this->tag = new ContainerTag('optgroup', [
            'label' => $label
        ]);
    }

    /**
     * Adds an option to this OptionGroup.
     *
     * @param string $content The text content of this option.
     * @param string $value   The value of this option.
     *
     * @return \Brick\Form\Element\Select\OptionGroup
     */
    public function addOption($content, $value)
    {
        $this->options[] = new Option($content, $value);

        return $this;
    }

    /**
     * Adds a batch of options to this OptionGroup.
     *
     * @param array $options The options as key-value pairs.
     *
     * @return \Brick\Form\Element\Select\OptionGroup
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
     * @return \Brick\Form\Element\Select\Option\Option[]
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
