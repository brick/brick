<?php

namespace Brick\Form\Element;

use Brick\Form\Element;
use Brick\Html\SelfClosingTag;
use Brick\Validation\Validator\PatternValidator;

/**
 * Represents an input element.
 */
abstract class Input extends Element
{
    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->tag = new SelfClosingTag('input');
        $this->tag->setAttribute('type', $this->getType());
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->tag->setAttribute('value', $value);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->tag->getAttribute('value');
    }

    /**
     * @param string $pattern
     *
     * @return static
     */
    public function setPattern($pattern)
    {
        $this->tag->setAttribute('pattern', $pattern);
        $this->removeValidators(PatternValidator::class);
        $this->addValidator(new PatternValidator($pattern));

        return $this;
    }

    /**
     * @return static
     */
    public function removePattern()
    {
        $this->tag->removeAttribute('pattern');
        $this->removeValidators(PatternValidator::class);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->getId(); // @todo find a better way to have it called once before render()
        return $this->tag->render();
    }

    /**
     * Returns the type of this input.
     *
     * @return string
     */
    abstract protected function getType();
}
