<?php

namespace Brick\Form\Element\Button;

use Brick\Form\Element\Button;

/**
 * Represents a submit button element.
 */
class Submit extends Button
{
    /**
     * @var boolean
     */
    private $pressed = false;

    /**
     * Returns whether this submit button has been pressed to submit the form.
     *
     * This is useful for forms with multiple submit buttons.
     *
     * @return boolean
     */
    public function isPressed()
    {
        return $this->pressed;
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->pressed = ($value === $this->getValue());
    }

    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'submit';
    }
}
