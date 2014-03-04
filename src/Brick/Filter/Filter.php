<?php

namespace Brick\Filter;

/**
 * Interface that all filters must implement.
 */
interface Filter
{
    /**
     * @param string $value The value to filter.
     *
     * @return string|null The filtered value, optionally null.
     */
    public function filter($value);
}
