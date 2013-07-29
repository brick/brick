<?php

namespace Brick\Http\Server;

use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Curl\Curl;

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

        if ($response === false) {
            // @todo This is probably not the right way. Should we define a specific exception for this?
            // In the case of Application, it would catch any exception and return a 500 error Response;
            // should we do the same here?
            throw new \RuntimeException('HTTP request failed: ' . curl_error($curl));
        }

        return Response::parse($response);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getHeaders(Request $request)
    {
        $headers = [];

        foreach ($request->getAllHeaders() as $header) {
            $headers[] = $header->toString();
        }

        // Overrides cURL sending an Expect header, to avoid receiving a 100 Continue.
        $headers[] = 'Expect:';

        return $headers;
    }
}
