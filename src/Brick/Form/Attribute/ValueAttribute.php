<?php

namespace Brick\Form\Attribute;

/**
 * Provides the value attribute to inputs.
 */
trait ValueAttribute
{
    use AbstractTag;

    /**
     * @param string|null $value
     *
     * @return static
     */
    public function setValue($value)
    {
        if ($value !== null) {
            $this->getTag()->setAttribute('value', $value);
        } else {
            $this->getTag()->removeAttribute('value');
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->getTag()->getAttribute('value');
    }
}
