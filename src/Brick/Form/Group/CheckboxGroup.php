<?php

namespace Brick\Form\Group;

use Brick\Form\Group;
use Brick\Form\Element\Input\Checkbox;

/**
 * Represents a group of checkboxes.
 */
class CheckboxGroup extends Group
{
    /**
     * The checkboxes in the group.
     *
     * @var \Brick\Form\Element\Input\Checkbox[]
     */
    private $checkboxes = [];

    /**
     * Adds a checkbox to this group and returns it.
     *
     * @return \Brick\Form\Element\Input\Checkbox
     */
    public function addCheckbox()
    {
        $checkbox = new Checkbox($this->form, $this->name . '[]');
        $this->checkboxes[] = $checkbox;

        return $checkbox;
    }

    /**
     * @param array $values
     *
     * @return static
     */
    public function setValues(array $values)
    {
        foreach ($this->checkboxes as $checkbox) {
            $checkbox->setChecked(in_array($checkbox->getValue(), $values, true));
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];

        foreach ($this->checkboxes as $checkbox) {
            if ($checkbox->isChecked()) {
                $values[] = $checkbox->getValue();
            }
        }

        return $values;
    }

    /**
     * @return \Brick\Form\Element\Input\Checkbox[]
     */
    public function getElements()
    {
        return $this->checkboxes;
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValues($value === null ? [] : $value);
    }

    /**
     * {@inheritdoc}
     */
    protected function isArray()
    {
        return true;
    }
}
