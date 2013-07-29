<?php

namespace Brick\Form\Element\Select;

use Brick\Form\Element\Select;

/**
 * Represents a multiple choice select element.
 */
class MultipleSelect extends Select
{
    /**
     * @param array $values
     * @return static
     */
    public function setValues(array $values)
    {
        foreach ($this->getOptions() as $option) {
            $option->setSelected(in_array($option->getValue(), $values));
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        $values = [];

        foreach ($this->getOptions() as $option) {
            if ($option->isSelected()) {
                $values[] = $option->getValue();
            }
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    protected function isArray()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function populate($value)
    {
        $this->setValues($value);
    }
}
