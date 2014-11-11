<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates elements by class name.
 */
class ByClassName extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->xPath = sprintf(
            'descendant::*[contains(concat(" ", normalize-space(@class), " "), " %s ")]',
            $className
        );
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
