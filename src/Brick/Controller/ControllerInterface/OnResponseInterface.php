<?php

namespace Brick\Controller\ControllerInterface;

use Brick\Http\Response;

/**
 * Controller classes implementing this interface will have the onResponse() method called after the action,
 * allowing them to modify the Response if needed (add cookies, etc.).
 *
 * This will only be called if the controller successfully returned a Response,
 * or if an HttpException has been thrown. If any other exception is thrown, onResponse() will *not* be called.
 *
 * The OnRequestResponseEventListener has to be registered in the application's EventDispatcher for this to work.
 */
interface OnResponseInterface
{
    /**
     * @param \Brick\Http\Response $response
     *
     * @return void
     */
    public function onResponse(Response $response);
}
