<?php

namespace Brick\Http\Server;

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Curl\Curl;

/**
 * cURL implementation of a request handler, that talks to an HTTP server.
 */
class CurlHandler implements RequestHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(Request $request)
    {
        $curl = new Curl();

        $curl->setOption(CURLOPT_HEADER, true);
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_URL, $request->getUrl());
        $curl->setOption(CURLOPT_HTTPHEADER, $this->getHeaders($request));
        $curl->setOption(CURLOPT_COOKIE, $request->getCookieString());

        if ($request->isPost()) {
            $curl->setOption(CURLOPT_POST, true);
        } elseif (! $request->isGet()) {
            $curl->setOption(CURLOPT_CUSTOMREQUEST, $request->getMethod());
        }

        switch ($request->getProtocolVersion()) {
            case Request::HTTP_1_0:
                $curl->setOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
                break;
            case Request::HTTP_1_1:
                $curl->setOption(CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                break;
        }

        if ($request->isPost()) {
            // @todo passing as an array will set Content-Type multipart/form-data
            // @todo this does not handle file uploads either
            $curl->setOption(CURLOPT_POSTFIELDS, $request->getPost());
        }

        $response = $curl->execute();

        return Response::parse($response);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getHeaders(Request $request)
    {
        $headers = [];

        foreach ($request->getHeaders() as $header) {
            $headers[] = $header->toString();
        }

        // Overrides cURL sending an Expect header, to avoid receiving a 100 Continue.
        $headers[] = 'Expect:';

        return $headers;
    }
}
