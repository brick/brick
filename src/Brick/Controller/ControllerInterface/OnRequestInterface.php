<?php

namespace Brick\Controller\ControllerInterface;

use Brick\Http\Request;

/**
 * Controller classes implementing this interface will have the onRequest() method called before the action,
 * allowing them to perform pre-action checks (throw HttpRedirectException, etc.).
 *
 * The OnRequestResponseEventListener has to be registered in the application's EventDispatcher for this to work.
 */
interface OnRequestInterface
{
    /**
     * @param \Brick\Http\Request $request
     *
     * @return void
     */
    public function onRequest(Request $request);
}
