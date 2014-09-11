<?php

namespace Brick\Application\Plugin;

use Brick\Application\Events;
use Brick\Application\Plugin;
use Brick\Event\EventDispatcher;
use Brick\Http\Request;
use Brick\Http\Response;
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
        $dispatcher->addListener(Events::INCOMING_REQUEST, function(Request $request) {
            $this->session->handleRequest($request);
        });

        $dispatcher->addListener(Events::RESPONSE_RECEIVED, function(Response $response) {
            $this->session->handleResponse($response);
        });
    }
}
