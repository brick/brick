<?php

namespace Brick\Application\Event;

use Brick\Event\Event;
use Brick\Http\Request;

/**
 * Base class for events having a Request (all events).
 */
abstract class AbstractRequestEvent extends Event
{
    /**
     * @var \Brick\Http\Request
     */
    private $request;

    /**
     * @param \Brick\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Brick\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
