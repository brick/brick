<?php

namespace Brick\Application\Event;

use Brick\Http\Request;

/**
 * Event dispatched after controller invocation, even if any kind of exception is caught.
 */
class ControllerInvocatedEvent extends AbstractControllerEvent
{
}
