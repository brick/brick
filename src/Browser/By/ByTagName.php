<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates elements by tag name.
 */
class ByTagName extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $tagName
     */
    public function __construct($tagName)
    {
        $this->xPath = 'descendant::' . $tagName;
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
