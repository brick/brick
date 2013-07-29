<?php

namespace Brick\View;

/**
 * A ViewRenderer is used to render a View as a string, optionally after applying
 * any modification it needs to (dependency injection, etc.).
 */
interface ViewRenderer
{
    /**
     * @param \Brick\View\View $view
     * @return string
     */
    public function render(View $view);
}
