<?php

namespace Brick\Form\Attribute;

/**
 * Provides the autocomplete attribute to inputs.
 */
trait AutocompleteAttribute
{
    use AbstractTag;

    /**
     * @param string $autocomplete
     *
     * @return static
     */
    public function setAutocomplete($autocomplete)
    {
        $this->getTag()->setAttribute('autocomplete', $autocomplete);

        return $this;
    }

    /**
     * @return string|null
     */
    public function isChecked()
    {
        return $this->getTag()->getAttribute('autocomplete');
    }
}
