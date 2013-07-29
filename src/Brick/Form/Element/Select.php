<?php

namespace Brick\Form\Element;

use Brick\Form\Element;
use Brick\Form\Element\Select\Option;
use Brick\Form\Element\Select\OptionGroup;
use Brick\Html\ContainerTag;

/**
 * Represents a select element.
 */
abstract class Select extends Element
{
    /**
     * This variable is purposefully redeclared to typehint the correct Tag subclass.
     *
     * @var \Brick\Html\ContainerTag
     */
    protected $tag;

    /**
     * The options and option groups in this Select.
     *
     * @var \Brick\Form\Element\Renderable[]
     */
    protected $elements = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->tag = new ContainerTag('select');
    }

    /**
     * @param string $content The text content of this option.
     * @param string $value   The value of this option.
     * @return Option
     */
    public function addOption($content, $value)
    {
        $option = new Option($content, $value);
        $this->elements[] = $option;

        return $option;
    }

    /**
     * Adds a batch of options to this Select.
     *
     * The array format is [value] => content.
     *
     * @param array $options
     * @return Select
     */
    public function addOptions(array $options)
    {
        foreach ($options as $value => $content) {
            $this->addOption($content, $value);
        }

        return $this;
    }

    /**
     * @param string $label The option group label.
     * @return OptionGroup
     */
    public function addOptionGroup($label)
    {
        $optionGroup = new Select\OptionGroup($label);
        $this->elements[] = $optionGroup;

        return $optionGroup;
    }

    /**
     * Returns all the options in this Select, including the ones nested in option groups.
     *
     * @return Option[]
     */
    protected function getOptions()
    {
        $options = [];

        foreach ($this->elements as $element) {
            if ($element instanceof Option) {
                $options[] = $element;
            }
            elseif ($element instanceof OptionGroup) {
                $options = array_merge($options, $element->getOptions());
            }
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->getId(); // @todo find a better way to have it called once before render()

        $content = '';

        foreach ($this->elements as $element) {
            $content .= $element->render();
        }

        return $this->tag->setHtmlContent($content)->render();
    }
}
