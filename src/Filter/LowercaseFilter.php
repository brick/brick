<?php

namespace Brick\Filter;

/**
 * Converts a string to lowercase.
 */
class LowerCaseFilter implements Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return strtolower($value);
    }
}
