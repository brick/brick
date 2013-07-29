<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates anchor elements by the exact text they display.
 */
class ByLinkText extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->xPath = sprintf('descendant::a[normalize-space(.) = "%s"]', $text);
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
