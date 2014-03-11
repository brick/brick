<?php

namespace Brick\Form\Attribute;

/**
 * Provides the checked attribute to inputs.
 */
trait CheckedAttribute
{
    use AbstractTag;

    /**
     * @param boolean $checked
     *
     * @return static
     */
    public function setChecked($checked)
    {
        if ($checked) {
            $this->getTag()->setAttribute('checked', 'checked');
        } else {
            $this->getTag()->removeAttribute('checked');
        }

        return $this;
    }

    /**
     * @return boolean
     */
    public function isChecked()
    {
        return $this->getTag()->hasAttribute('checked');
    }
}
