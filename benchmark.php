<?php

require 'vendor/autoload.php';

use Brick\App\Application;
use Brick\Http\Request;
use Brick\Http\Response;
use Brick\App\Routing\Route;
use Brick\App\Routing\RouteMatch;
use Brick\App\Event\NonResponseResultEvent;

class MyRoute implements Route{
    public function match(Request $request) {
        return RouteMatch::forFunction(function() {
            return ["test" => "hop"];
        });
    }
}

class JsonPlugin implements \Brick\App\Plugin
{
    public function register(\Brick\Event\EventDispatcher $dispatcher)
    {
        $dispatcher->addListener(NonResponseResultEvent::class, function (NonResponseResultEvent $event) {
            $response = new Response();
            $response->setContent(json_encode($event->getControllerResult()));
            $response->setHeader('Content-Type', 'application/json');
            $event->setResponse($response);
        });
    }
}

$application = Application::create();
$application->addRoute(new MyRoute());
$application->addPlugin(new JsonPlugin());

$application->run();
