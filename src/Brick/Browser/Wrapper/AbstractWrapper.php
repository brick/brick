<?php

namespace Brick\Browser\Wrapper;

use Brick\Browser\Element;

/**
 * Wraps a web element to extend its functionality.
 */
abstract class AbstractWrapper
{
    /**
     * The wrapped HTML element.
     *
     * @var Element
     */
    protected $element;

    /**
     * Class constructor.
     *
     * @param Element $element
     */
    protected function __construct(Element $element)
    {
        $this->element = $element;
    }

    /**
     * @return Element
     */
    public function getWrappedElement()
    {
        return $this->element;
    }
}
