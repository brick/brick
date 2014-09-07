<?php

namespace Brick\Application;

/**
 * Event types dispatched by the application.
 */
final class Events
{
    /**
     * Dispatched as soon as the application receives a Request.
     *
     * This is a `RequestEvent`.
     */
    const INCOMING_REQUEST = 'IncomingRequest';

    /**
     * Dispatched after the router has returned a match.
     *
     * This is a `RouteMatchEvent`.
     */
    const ROUTE_MATCHED = 'RouteMatched';

    /**
     * Dispatched when the controller is ready to be invoked.
     *
     * This event allows to provide the controller with parameters.
     *
     * This is a `ControllerParameterEvent`.
     */
    const CONTROLLER_READY = 'ControllerReady';

    /**
     * Dispatched after controller invocation, regardless of whether an exception was thrown.
     *
     * This is a `ControllerEvent`.
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
     * This is a `ResponseEvent`.
     */
    const RESPONSE_RECEIVED = 'ResponseReceived';

    /**
     * Dispatched as soon as an exception is caught.
     *
     * If the exception is not an HttpException, it is wrapped in an HttpInternalServerErrorException first,
     * so that this event always receives an HttpException.
     *
     * A default response is created to display the details of the exception.
     * This event allows to change the default response to display a customized error message to the user.
     *
     * This is an `ExceptionEvent`.
     */
    const EXCEPTION_CAUGHT = 'ExceptionCaught';
}
