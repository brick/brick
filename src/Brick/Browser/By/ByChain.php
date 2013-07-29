<?php

namespace Brick\Browser\By;

use Brick\Browser\By;

/**
 * Locates elements using a series of other lookups.
 *
 * This class will find all elements that match each of the locators in sequence, e.g.
 * `find(By::chain($by1, $by2))` will find all elements that match `$by2`
 * and appear under an element that matches `$by1`.
 */
class ByChain extends By
{
    /**
     * @var By[]
     */
    private $bys;

    /**
     * @param By[] $bys
     */
    public function __construct(array $bys)
    {
        $this->bys = $bys;
    }

    /**
     * @param \DOMElement[] $elements
     * @return \DOMElement[]
     */
    public function findElements(array $elements)
    {
        foreach ($this->bys as $by) {
            $elements = $by->findElements($elements);
        }

        return $elements;
    }
}
