<?php

namespace Brick\Application;

/**
 * List of events dispatched by the application.
 */
final class Events
{
    /**
     * Dispatched as soon as the application receives a Request.
     *
     * This event provides the following parameters:
     *
     * - The incoming request: `Brick\Http\Request`.
     */
    const INCOMING_REQUEST = 'IncomingRequest';

    /**
     * Dispatched after the router has returned a match.
     *
     * This event provides the following parameters:
     *
     * - The route match: `Brick\Routing\RouteMatch`.
     * - The incoming request: `Brick\Http\Request`.
     */
    const ROUTE_MATCHED = 'RouteMatched';

    /**
     * Dispatched when the controller is ready to be invoked.
     *
     * This event provides the following parameters:
     *
     * - The controller instance if the controller is a method: `object|null`.
     * - The route match: `Brick\Routing\RouteMatch`.
     * - The incoming request: `Brick\Http\Request`.
     * - A storage for controller parameters: `Brick\Application\ParameterMap`.
     *   This storage can receive key-value pairs that will be used to resolve the controller parameters.
     */
    const CONTROLLER_READY = 'ControllerReady';

    /**
     * Dispatched after controller invocation, regardless of whether an exception was thrown.
     *
     * This event provides the following parameters:
     *
     * - The controller instance if the controller is a method: `object|null`.
     * - The route match: `Brick\Routing\RouteMatch`.
     * - The incoming request: `Brick\Http\Request`.
     */
    const CONTROLLER_INVOCATED = 'ControllerInvocated';

    /**
     * Dispatched after the controller response has been received.
     *
     * If an HttpException is caught during the controller method invocation,
     * the exception it is converted to a Response, and this event is dispatched as well.
     *
     * Other exceptions break the application flow and don't trigger this event.
     *
     * This event provides the following parameters:
     *
     * - The response object: `Brick\Http\Response`.
     * - The controller instance if the controller is a method: `object|null`.
     * - The route match: `Brick\Routing\RouteMatch`.
     * - The incoming request: `Brick\Http\Request`.
     */
    const RESPONSE_RECEIVED = 'ResponseReceived';

    /**
     * Dispatched as soon as an exception is caught.
     *
     * If the exception is not an HttpException, it is wrapped in an HttpInternalServerErrorException first,
     * so that this event always receives an HttpException.
     *
     * A default response is created to display the details of the exception.
     * This event provides an opportunity to modify the default response
     * to present a customized error message to the client.
     *
     * - The HTTP exception: `Brick`Http\Exception\HttpException`.
     * - The incoming request: `Brick\Http\Request`.
     * - The response object: `Brick\Http\Response`.
     */
    const EXCEPTION_CAUGHT = 'ExceptionCaught';
}
