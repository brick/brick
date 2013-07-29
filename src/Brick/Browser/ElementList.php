<?php

namespace Brick\Browser;

/**
 * A list of web elements.
 */
class ElementList extends SearchContext implements Target, \Countable, \IteratorAggregate
{
    /**
     * @var \DOMElement[]
     */
    private $domElements;

    /**
     * @var Element[]
     */
    private $elements = array();

    /**
     * @param \DOMElement[] $domElements
     */
    public function __construct(array $domElements)
    {
        $this->domElements = $domElements;

        foreach ($domElements as $domElement) {
            $this->elements[] = new Element($domElement);
        }
    }

    /**
     * Ensures that there is only one element in the list, and returns this element.
     *
     * @return Element
     *
     * @throws Exception\NoSuchElementException   If the list is empty.
     * @throws Exception\TooManyElementsException If the list contains more than one element.
     */
    public function one()
    {
        if ($this->count() > 1) {
            throw Exception\TooManyElementsException::expectedOne($this->count());
        }

        return $this->first();
    }

    /**
     * Returns all elements as an array.
     *
     * @return Element[]
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * Returns an element by its 0-based index in the list.
     *
     * @param int $index
     *
     * @return Element
     *
     * @throws Exception\NoSuchElementException If the index does not exist.
     */
    public function get($index)
    {
        if (isset($this->elements[$index])) {
            return $this->elements[$index];
        }

        throw Exception\NoSuchElementException::undefinedIndex($index);
    }

    /**
     * Returns the first element in the list.
     *
     * @return Element
     *
     * @throws Exception\NoSuchElementException If the list is empty.
     */
    public function first()
    {
        if ($this->count() == 0) {
            throw Exception\NoSuchElementException::emptyList();
        }

        return $this->elements[0];
    }

    /**
     * Returns the last element in the list.
     *
     * @return Element
     *
     * @throws Exception\NoSuchElementException If the list is empty.
     */
    public function last()
    {
        return $this->elements[$this->count() - 1];
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    protected function getElements()
    {
        return $this->domElements;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetElement(Browser $browser)
    {
        return $this->one();
    }
}
