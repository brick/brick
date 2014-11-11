<?php

namespace Brick\Form\Attribute;

use Brick\Validation\Validator;

/**
 * Enforces the presence of an HTML tag.
 */
trait AbstractTag
{
    /**
     * @return \Brick\Html\Tag
     */
    abstract protected function getTag();

    /**
     * @param \Brick\Validation\Validator $validator
     *
     * @return static
     */
    abstract protected function addValidator(Validator $validator);

    /**
     * @param string $className
     *
     * @return static
     */
    abstract protected function removeValidators($className);
}
