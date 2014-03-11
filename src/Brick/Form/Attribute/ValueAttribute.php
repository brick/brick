<?php

namespace Brick\Form\Attribute;

/**
 * Provides the value attribute to inputs.
 */
trait ValueAttribute
{
    use AbstractTag;

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->getTag()->setAttribute('value', $value);

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
