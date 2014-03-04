<?php

namespace Brick\Filter;

/**
 * Returns null for an empty string.
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
