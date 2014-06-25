<?php

namespace Brick\Http\Server;

use Brick\Http\Request;
use Brick\Http\Response;

/**
 * Network implementation of a request handler, that talks to an HTTP server.
 */
class NetworkHandler implements RequestHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Request $request)
    {
        $request->setHeader('Connection', 'close');

        $path = $request->getHost();

        if ($request->isSecure()) {
            $path = 'ssl://' . $path;
        }

        $fp = fsockopen($path, $request->getPort());

        fwrite($fp, $request);

        return Response::parse(stream_get_contents($fp));
    }
}
