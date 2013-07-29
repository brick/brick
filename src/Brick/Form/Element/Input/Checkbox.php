<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a checkbox input element.
 */
class Checkbox extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'checkbox';
    }

    /**
     * @param bool $checked
     * @return static
     */
    public function setChecked($checked)
    {
        if ($checked) {
            $this->tag->setAttribute('checked', 'checked');
        } else {
            $this->tag->removeAttribute('checked');
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        return $this->tag->hasAttribute('checked');
    }

    /**
     * {@inheritdoc}
     */
    protected function populate($value)
    {
        $this->setChecked($value == $this->getValue());
    }
}
