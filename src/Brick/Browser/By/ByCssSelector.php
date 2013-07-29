<?php

namespace Brick\Browser\By;

use Brick\Browser\By;
use Symfony\Component\CssSelector\CssSelector;

/**
 * Locates elements by CSS selector.
 */
class ByCssSelector extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $selector
     */
    public function __construct($selector)
    {
        $this->xPath = CssSelector::toXPath($selector, 'descendant::');
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
