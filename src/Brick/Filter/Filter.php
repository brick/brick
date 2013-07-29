<?php

namespace Brick\Filter;

/**
 * Interface that all filters must implement.
 */
interface Filter
{
    /**
     * @param  mixed $value              The value to filter.
     * @return mixed                     The filtered value.
     * @throws \InvalidArgumentException If the value to filter is not valid.
     */
    public function filter($value);
}
