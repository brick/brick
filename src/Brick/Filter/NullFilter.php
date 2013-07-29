<?php

namespace Brick\Filter;

/**
 * This filter returns null for an empty string.
 */
class NullFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return ($value === '') ? null : $value;
    }
}
