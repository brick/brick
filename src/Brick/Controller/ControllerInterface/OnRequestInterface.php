<?php

namespace Brick\Controller\ControllerInterface;

use Brick\Http\Request;

/**
 * Controller classes implementing this interface will have the onRequest() method called before the controller method.
 *
 * This method is allowed to throw HTTP exceptions, and therefore can perform checks common to all
 * controller methods in the class, and redirect / return an HTTP error code if necessary.
 *
 * This interface requires the OnRequestResponseListener to be registered with the application.
 */
interface OnRequestInterface
{
    /**
     * @param \Brick\Http\Request $request
     *
     * @return void
     *
     * @throws \Brick\Http\Exception\HttpException
     */
    public function onRequest(Request $request);
}
