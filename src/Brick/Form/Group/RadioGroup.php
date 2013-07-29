<?php

namespace Brick\Form\Group;

use Brick\Form\Group;
use Brick\Form\Element\Input\Radio;

/**
 * Represents a group of radio buttons.
 */
class RadioGroup extends Group
{
    /**
     * The radio buttons in the group.
     *
     * @var \Brick\Form\Element\Input\Radio[]
     */
    private $radios = [];

    /**
     * Adds a radio button in this group and returns it.
     *
     * @return \Brick\Form\Element\Input\Radio
     */
    public function addRadio()
    {
        $radio = new Radio($this->form, $this->name);
        $this->radios[] = $radio;

        return $radio;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue($value)
    {
        foreach ($this->radios as $radioButton) {
            $radioButton->setChecked($radioButton->getValue() == $value);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        foreach ($this->radios as $radioButton) {
            if ($radioButton->isChecked()) {
                return $radioButton->getValue();
            }
        }

        return null;
    }

    /**
     * @return \Brick\Form\Element\Input\Radio[]
     */
    public function getElements()
    {
        return $this->radios;
    }

    /**
     * {@inheritdoc}
     */
    protected function populate($value)
    {
        $this->setValue($value);
    }
}
