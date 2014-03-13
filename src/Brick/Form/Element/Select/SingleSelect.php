<?php

namespace Brick\Form\Element\Select;

use Brick\Form\Element\Select;

/**
 * Represents a single choice select element.
 */
class SingleSelect extends Select
{
    /**
     * @param string|null $value
     *
     * @return static
     */
    public function setValue($value)
    {
        foreach ($this->getOptions() as $option) {
            $option->setSelected($value !== null && $option->getValue() === $value);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        foreach ($this->getOptions() as $option) {
            if ($option->isSelected()) {
                return $option->getValue();
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function doPopulate($value)
    {
        $this->setValue($value);
    }
}
