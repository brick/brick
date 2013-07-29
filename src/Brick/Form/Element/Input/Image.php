<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents an image input element.
 *
 * @todo instead of throwing a BadMethodCallException, why not factor the getValue() and setValue()
 * in an abstract parent class, or in a trait?
 */
class Image extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     * @throws \BadMethodCallException
     */
    public function setValue($value)
    {
        throw new \BadMethodCallException('Image input cannot have a value');
    }

    /**
     * @inheritdoc
     * @throws \BadMethodCallException
     */
    public function getValue()
    {
        throw new \BadMethodCallException('Image input does not have a value');
    }
}
