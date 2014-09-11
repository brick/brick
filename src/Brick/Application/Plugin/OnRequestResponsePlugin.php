<?php

namespace Brick\Application\Plugin;

use Brick\Application\Events;
use Brick\Application\Plugin;
use Brick\Application\Controller\Interfaces\OnRequestInterface;
use Brick\Application\Controller\Interfaces\OnResponseInterface;
use Brick\Event\EventDispatcher;
use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Routing\RouteMatch;

/**
 * Calls `onRequest()` and `onResponse()` on controllers implementing OnRequestInterface and OnResponseInterface.
 */
class OnRequestResponsePlugin implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(Events::CONTROLLER_READY, [$this, 'invokeOnRequest']);
        $dispatcher->addListener(Events::RESPONSE_RECEIVED, [$this, 'invokeOnResponse']);
    }

    /**
     * @internal
     *
     * @param object|null $controller
     * @param RouteMatch  $match
     * @param Request     $request
     *
     * @return void
     */
    public function invokeOnRequest($controller, $match, Request $request)
    {
        if ($controller instanceof OnRequestInterface) {
            $controller->onRequest($request);
        }
    }

    /**
     * @internal
     *
     * @param Response    $response
     * @param object|null $controller
     *
     * @return void
     */
    public function invokeOnResponse(Response $response, $controller)
    {
        if ($controller instanceof OnResponseInterface) {
            $controller->onResponse($response);
        }
    }
}
