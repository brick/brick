<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates elements by XPath.
 */
class ByXPath extends By
{
    /**
     * @var string
     */
    private $xPath;

    /**
     * @param string $xPath
     */
    public function __construct($xPath)
    {
        $this->xPath = $xPath;
    }

    /**
     * @param \DOMElement[] $elements
     * @return \DOMElement[]
     */
    public function findElements(array $elements)
    {
        $result = [];

        foreach ($elements as $element) {
            $domxpath = new \DOMXPath($element->ownerDocument);
            $nodelist = $domxpath->query($this->xPath, $element);
            $result = array_merge($result, iterator_to_array($nodelist));
        }

        return $result;
    }
}
