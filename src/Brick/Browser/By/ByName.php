<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates elements by name.
 */
class ByName extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->xPath = sprintf('descendant::*[@name = "%s"]', $name);
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
