<?php

namespace Brick\Filter;

/**
 * Removes non (ASCII) alphanumeric characters.
 */
class NonAlnumFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return preg_replace('/[^0-9a-zA-Z]/', '', $value);
    }
}
