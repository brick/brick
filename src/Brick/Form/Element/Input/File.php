<?php

namespace Brick\Form\Element\Input;

use Brick\Form\Element\Input;

/**
 * Represents a file input element.
 *
 * @todo same comment as for Image
 */
class File extends Input
{
    /**
     * {@inheritdoc}
     */
    protected function getType()
    {
        return 'file';
    }

    /**
     * @inheritdoc
     * @throws \BadMethodCallException
     */
    public function setValue($value)
    {
        throw new \BadMethodCallException('File input cannot have a value');
    }

    /**
     * @inheritdoc
     * @throws \BadMethodCallException
     */
    public function getValue()
    {
        throw new \BadMethodCallException('File input does not have a value');
    }
}
