<?php

namespace Brick\Form\Element\Select\Option;

/**
 * Base class for Option and OptionGroup.
 */
abstract class OptionOrGroup
{
    /**
     * @return string
     */
    abstract public function render();
}
