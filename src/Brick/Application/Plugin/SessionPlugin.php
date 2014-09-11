<?php

namespace Brick\Application\Plugin;

use Brick\Application\Event\IncomingRequestEvent;
use Brick\Application\Event\ResponseReceivedEvent;
use Brick\Application\Plugin;
use Brick\Event\EventDispatcher;
use Brick\Session\Session;

/**
 * Integrates session management in the request/response process.
 */
class SessionPlugin implements Plugin
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
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(IncomingRequestEvent::class, function(IncomingRequestEvent $event) {
            $this->session->handleRequest($event->getRequest());
        });

        $dispatcher->addListener(ResponseReceivedEvent::class, function(ResponseReceivedEvent $event) {
            $this->session->handleResponse($event->getResponse());
        });
    }
}
