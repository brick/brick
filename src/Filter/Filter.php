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
     * @return string The filtered value.
     */
    public function filter($value);
}
