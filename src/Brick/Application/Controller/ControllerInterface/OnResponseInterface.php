<?php

namespace Brick\Application\Controller\ControllerInterface;

use Brick\Http\Response;

/**
 * Controller classes implementing this interface will have the onResponse() method called after the controller method.
 *
 * This allows to modify the Response if needed.
 *
 * This will only be called if the controller successfully returns a Response,
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
