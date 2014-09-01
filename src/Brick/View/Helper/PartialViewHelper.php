<?php

namespace Brick\View\Helper;

use Brick\View\View;
use Brick\View\ViewRenderer;

/**
 * This view helper allows to render a View from within another View (referred to as a partial view).
 * The rendering of the partial view is done wia a ViewRenderer, which allows to inject dependencies into it.
 */
trait PartialViewHelper
{
    /**
     * @var \Brick\View\ViewRenderer|null
     */
    private $viewRenderer;

    /**
     * @Brick\Di\Annotation\Inject
     *
     * @param \Brick\View\ViewRenderer $viewRenderer
     * @return void
     */
    final public function setViewRenderer(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * Renders a partial View.
     *
     * @param \Brick\View\View $view The View object to render.
     * @return string The rendered View.
     * @throws \RuntimeException If the view renderer has not been injected.
     */
    final public function partial(View $view)
    {
        if (! $this->viewRenderer) {
            throw new \RuntimeException('No view renderer has been registered');
        }

        return $this->viewRenderer->render($view);
    }
}
