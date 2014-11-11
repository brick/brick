<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates elements by id.
 */
class ById extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->xPath = sprintf('descendant::*[@id = "%s"]', $id);
    }

    /**
     * @param \DOMElement[] $elements
     * @return \DOMElement[]
     */
    public function findElements(array $elements)
    {
        return By::xPath($this->xPath)->findElements($elements);
    }
}
