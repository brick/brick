<?php

namespace Brick\Browser\Wrapper;

use Brick\Browser\Element;
use Brick\Browser\Exception\UnexpectedElementException;

/**
 * Base class for CheckBox and RadioButton.
 */
abstract class ToggleButton extends FormControl
{
    /**
     * @param Element $element
     * @return ToggleButton
     * @throws UnexpectedElementException
     */
    public static function create(Element $element)
    {
        if (! $element->is('input')) {
            throw new UnexpectedElementException('Expected input element, got ' . $element->getTagName());
        }

        switch ($type = $element->getAttribute('type')) {
            case 'checkbox':
                return new CheckBox($element);

            case 'radio':
                return new RadioButton($element);

            default:
                throw new UnexpectedElementException('Expected input type=checkbox|radiobutton, got ' . $type);
        }
    }

    /**
     * Returns whether the control is currently checked.
     *
     * @return boolean
     */
    public function isChecked()
    {
        return $this->element->hasAttribute('checked');
    }

    /**
     * Returns the value attribute of the toggle button, or 'on' if no value is given.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->element->hasAttribute('value') ? $this->element->getAttribute('value') : 'on';
    }

    /**
     * Checks the control.
     *
     * @return void
     */
    public function check()
    {
        $this->element->setAttribute('checked', 'checked');
    }

    /**
     * Unchecks the control.
     *
     * @return void
     */
    public function uncheck()
    {
        $this->element->removeAttribute('checked');
    }

    /**
     * Toggles the control state.
     *
     * @return void
     */
    public function toggle()
    {
        $this->element->hasAttribute('checked') ? $this->uncheck() : $this->check();
    }
}
