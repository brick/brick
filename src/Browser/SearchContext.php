<?php

namespace Brick\Browser;

/**
 * Base class for all searchable classes.
 */
abstract class SearchContext
{
    /**
     * @param By $by
     * @return ElementList
     */
    public function find(By $by)
    {
        return new ElementList($this->deduplicate($by->findElements($this->getElements())));
    }

    /**
     * Removes duplicates from an array of DOM elements.
     *
     * @param \DOMElement[] $elements
     * @return \DOMElement[]
     */
    private function deduplicate(array $elements)
    {
        $result = [];

        foreach ($elements as $element) {
            $result[spl_object_hash($element)] = $element;
        }

        return array_values($result);
    }

    /**
     * @param By $by
     * @return Wrapper\TextControl
     */
    public function findTextControl(By $by)
    {
        return $this->find($by)->one()->toTextControl();
    }

    /**
     * @param By $by
     * @return Wrapper\ToggleButton
     */
    public function findToggleButton(By $by)
    {
        return $this->find($by)->one()->toToggleButton();
    }

    /**
     * @param By $by
     * @return Wrapper\Select
     */
    public function findSelect(By $by)
    {
        return $this->find($by)->one()->toSelect();
    }

    /**
     * @param By $by
     * @return Wrapper\FileInput
     */
    public function findFileInput(By $by)
    {
        return $this->find($by)->one()->toFileInput();
    }

    /**
     * Returns an array of elements to search into.
     *
     * @return \DOMElement[]
     */
    abstract protected function getElements();
}
