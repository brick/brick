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
        $this->getTag()->setAttribute('value', (string) $value);

        return $this;
    }

    /**
     * Returns the value of the input.
     *
     * @return string
     */
    public function getValue()
    {
        $value = $this->getTag()->getAttribute('value');

        return $value === null ? '' : $value;
    }

    /**
     * Returns the value of the input, or null if empty.
     *
     * @return string|null
     */
    public function getValueOrNull()
    {
        $value = $this->getTag()->getAttribute('value');

        return $value === '' ? null : $value;
    }
}
