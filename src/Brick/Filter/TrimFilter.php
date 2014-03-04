<?php

namespace Brick\Filter;

/**
 * Trims whitespaces around a string.
 */
class TrimFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return trim($value);
    }
}
