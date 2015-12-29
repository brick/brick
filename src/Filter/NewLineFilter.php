<?php

namespace Brick\Filter;

/**
 * Filters out new lines in a string.
 */
class NewLineFilter implements Filter
{
    /**
     * @var string
     */
    private $replaceWith = '';

    /**
     * @param string $replaceWith
     */
    public function __construct($replaceWith = '')
    {
        $this->replaceWith = $replaceWith;
    }

    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        return str_replace(["\r\n", "\r", "\n"], $this->replaceWith, $value);
    }
}
