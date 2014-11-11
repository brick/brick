<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates anchor elements that contain the given link text.
 */
class ByPartialLinkText extends By
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
        $this->xPath = sprintf('descendant::a[contains(., "%s")]', $text);
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
