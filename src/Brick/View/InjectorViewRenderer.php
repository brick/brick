<?php

namespace Brick\View;

use Brick\DependencyInjection\Injector;

/**
 * Injects dependencies in the View before rendering it.
 */
class InjectorViewRenderer implements ViewRenderer
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @param Injector $injector
     */
    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * {@inheritdoc}
     */
    public function render(View $view)
    {
        $this->injector->inject($view);

        return $view->render();
    }
}
