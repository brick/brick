<?php

namespace Brick\Browser;

/**
 * A target for click() and submit() in the Browser.
 * Implemented by Element, ElementList, and By.
 */
interface Target
{
    /**
     * @param Browser $browser
     *
     * @return Element
     *
     * @throws Exception\NoSuchElementException
     * @throws Exception\TooManyElementsException
     */
    public function getTargetElement(Browser $browser);
}
