<?php

namespace Brick\Form;

use Brick\Validation\Validator\RequiredValidator;

/**
 * Base class for form elements.
 */
abstract class Element extends Component
{
    /**
     * @var \Brick\Html\Tag
     */
    protected $tag;

    /**
     * @var \Brick\Form\Label|null
     */
    private $label = null;

    /**
     * @return string
     */
    public function getId()
    {
        if (! $this->tag->hasAttribute('id')) {
            $id = $this->form->generateElementId($this);
            $this->tag->setAttribute('id', $id);
        }

        return $this->tag->getAttribute('id');
    }

    /**
     * @param boolean $required
     *
     * @return static
     */
    public function setRequired($required)
    {
        /**
         * This validator instance is shared across all elements.
         *
         * @var \Brick\Validation\Validator\RequiredValidator
         */
        static $notBlankValidator = null;

        if ($notBlankValidator === null) {
            $notBlankValidator = new RequiredValidator();
        }

        if ($required) {
            $this->tag->setAttribute('required', 'required');
            $this->addValidator($notBlankValidator);
        } else {
            $this->tag->removeAttribute('required');
            $this->removeValidator($notBlankValidator);
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->tag->hasAttribute('required');
    }

    /**
     * @param boolean $disabled
     *
     * @return static
     */
    public function setDisabled($disabled)
    {
        if ($disabled) {
            $this->tag->setAttribute('disabled', 'disabled');
        } else {
            $this->tag->removeAttribute('disabled');
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->tag->hasAttribute('disabled');
    }

    /**
     * {@inheritdoc}
     */
    protected function setName($name)
    {
        $this->tag->setAttribute('name', $name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->tag->getAttribute('name');
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
        $this->tag->setAttribute($name, $value);

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
     * @return string
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
