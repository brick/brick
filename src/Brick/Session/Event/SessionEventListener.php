<?php

namespace Brick\Session\Event;

use Brick\Event\Event;
use Brick\Event\EventListener;
use Brick\Application\Event\IncomingRequestEvent;
use Brick\Application\Event\ResponseReceivedEvent;
use Brick\Session\Session;

/**
 * Event listener to integrate session management in the request/response process.
 */
class SessionEventListener implements EventListener
{
    /**
     * @var \Brick\Session\Session
     */
    private $session;

    /**
     * @param \Brick\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function handleEvent(Event $event)
    {
        if ($event instanceof IncomingRequestEvent) {
            $this->session->handleRequest($event->getRequest());
        }

        if ($event instanceof ResponseReceivedEvent) {
            $this->session->handleResponse($event->getResponse());
        }
    }
}
