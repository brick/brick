<?php

namespace Brick\Form;

/**
 * Base class for form elements.
 */
abstract class Element extends Component
{
    /**
     * @var \Brick\Form\Label|null
     */
    private $label;

    /**
     * @return string
     */
    public function getId()
    {
        $tag = $this->getTag();

        if (! $tag->hasAttribute('id')) {
            $id = $this->form->generateElementId($this);
            $tag->setAttribute('id', $id);
        }

        return $tag->getAttribute('id');
    }

    /**
     * @param boolean $required
     *
     * @return static
     */
    public function setRequired($required)
    {
        if ($required) {
            $this->getTag()->setAttribute('required', 'required');
        } else {
            $this->getTag()->removeAttribute('required');
        }

        return parent::setRequired($required);
    }

    /**
     * @param boolean $disabled
     *
     * @return static
     */
    public function setDisabled($disabled)
    {
        if ($disabled) {
            $this->getTag()->setAttribute('disabled', 'disabled');
        } else {
            $this->getTag()->removeAttribute('disabled');
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->getTag()->hasAttribute('disabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function setName($name)
    {
        $this->getTag()->setAttribute('name', $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getTag()->getAttribute('name');
    }

    /**
     * @todo sanity check: should not be used for 'required', 'disabled', etc.
     *
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function setAttribute($name, $value)
    {
        $this->getTag()->setAttribute($name, $value);

        return $this;
    }

    /**
     * @return \Brick\Form\Label
     */
    public function getLabel()
    {
        if ($this->label === null) {
            $this->label = new Label($this);
        }

        return $this->label;
    }

    /**
     * Convenience method to set the text content of the element's label.
     *
     * @param string $label
     *
     * @return static
     */
    public function setLabel($label)
    {
        $this->getLabel()->setTextContent($label);

        return $this;
    }

    /**
     * Returns the HTML tag of this element.
     *
     * @return \Brick\Html\Tag
     */
    abstract protected function getTag();

    /**
     * Renders the element.
     *
     * If the element doesn't have an id, one is automatically assigned.
     *
     * @return string
     */
    public function render()
    {
        $this->getId();
        $this->onBeforeRender();

        return $this->getTag()->render();
    }

    /**
     * Called before the HTML tag is rendered.
     *
     * @return void
     */
    protected function onBeforeRender()
    {
    }

    /**
     * Convenience magic method to render the element.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
