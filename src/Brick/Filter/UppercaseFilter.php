<?php

namespace Brick\Filter;

/**
 * Converts a string to uppercase.
 */
class UpperCaseFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return strtoupper($value);
    }
}
