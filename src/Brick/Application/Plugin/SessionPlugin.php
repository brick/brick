<?php

namespace Brick\Application\Plugin;

use Brick\Application\Event\RequestEvent;
use Brick\Application\Event\ResponseEvent;
use Brick\Application\Events;
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
        $dispatcher->addListener(Events::INCOMING_REQUEST, function(RequestEvent $event) {
            $this->session->handleRequest($event->getRequest());
        });

        $dispatcher->addListener(Events::RESPONSE_RECEIVED, function(ResponseEvent $event) {
            $this->session->handleResponse($event->getResponse());
        });
    }
}
