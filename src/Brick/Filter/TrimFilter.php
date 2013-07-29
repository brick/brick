<?php

namespace Brick\Filter;

/**
 * This filters trims whitespaces around a string.
 */
class TrimFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        if (! is_null($value) && ! is_string($value)) {
            throw new \InvalidArgumentException('Value must be a strinf or null');
        }

        return trim($value);
    }
}
