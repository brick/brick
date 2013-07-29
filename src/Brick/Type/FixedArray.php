<?php

namespace Brick\Type;

/**
 * Adds convenient methods to move elements in an SplFixedArray.
 */
class FixedArray extends \SplFixedArray
{
    /**
     * Moves an element to an arbitrary index.
     *
     * @param int $currentIndex
     * @param int $newIndex
     * @return void
     */
    public function moveTo($currentIndex, $newIndex)
    {
        while ($currentIndex > $newIndex) {
            $this->moveDown($currentIndex);
            $currentIndex--;
        }
        while ($currentIndex < $newIndex) {
            $this->moveUp($currentIndex);
            $currentIndex++;
        }
    }

    /**
     * Moves an element to the next index.
     *
     * @param int $index
     * @return void
     */
    public function moveUp($index)
    {
        $element = $this[$index];
        $this[$index] = $this[$index + 1];
        $this[$index + 1] = $element;
    }

    /**
     * Moves an element to the previous index.
     *
     * @param int $index
     * @return void
     */
    public function moveDown($index)
    {
        $element = $this[$index];
        $this[$index] = $this[$index - 1];
        $this[$index - 1] = $element;
    }

    /**
     * Returns a FixedArray instead of a SplFixedArray, as for now the latter does not return "new static".
     * @see https://bugs.php.net/bug.php?id=55128
     *
     * @param array $array
     * @param bool $saveIndexes
     * @return FixedArray
     */
    public static function fromArray($array, $saveIndexes = true)
    {
        $array = \SplFixedArray::fromArray($array);
        $result = new static($array->count());

        foreach ($array as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }
}
