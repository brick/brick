<?php

namespace Brick\Http\Client;

use Brick\Http\Listener\MessageListener;
use Brick\Http\Server\RequestHandler;
use Brick\Http\Request;

/**
 * A simple HTTP client.
 */
class Client
{
    /**
     * The handler to serve the requests.
     *
     * @var \Brick\Http\Server\RequestHandler
     */
    private $requestHandler;

    /**
     * An array of headers that will be sent with each request.
     * Typical values are User-Agent, Accept, Accept-Language, etc.
     *
     * @var array
     */
    private $headers = [];

    /**
     * Whether to automatically follow redirects.
     *
     * This is ON by default, but can be turned off to manually control redirections.
     *
     * @var boolean
     */
    private $autoFollowRedirects = true;

    /**
     * The last request sent, if any.
     *
     * @var \Brick\Http\Request|null
     */
    private $lastRequest = null;

    /**
     * The last response received, if any.
     *
     * @var \Brick\Http\Response|null
     */
    private $lastResponse = null;

    /**
     * The browser cookie storage.
     *
     * @var \Brick\Http\Client\CookieStore
     */
    private $cookieStore;

    /**
     * @var \Brick\Http\Listener\MessageListener|null
     */
    private $messageListener;

    /**
     * Class constructor.
     *
     * @param \Brick\Http\Server\RequestHandler         $handler  A handler to serve the requests.
     * @param \Brick\Http\Client\CookieStore|null       $store    An optional cookie store to re-use.
     * @param \Brick\Http\Listener\MessageListener|null $listener An optional message listener.
     */
    public function __construct(RequestHandler $handler, CookieStore $store = null, MessageListener $listener = null)
    {
        $this->requestHandler = $handler;
        $this->cookieStore = $store ?: new CookieStore();
        $this->messageListener = $listener;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $headers
     *
     * @return RequestResponse
     */
    public function request($method, $url, array $headers = [])
    {
        return $this->doRequest($method, $url, $headers, true);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $post
     * @param array  $cookies
     * @param array  $headers
     *
     * @return \Brick\Http\Request
     */
    public function createRequest($method, $url, array $post = [], array $cookies = [], array $headers = [])
    {
        return (new Request())
            ->setMethod($method)
            ->setUrl($url)
            ->setPost($post)
            ->setCookies($cookies)
            ->setHeaders($headers + $this->headers);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $headers
     *
     * @return RequestResponse
     */
    private function doRequest($method, $url, array $headers)
    {
        return $this->rawRequest($this->createRequest($method, $url, $headers));
    }

    /**
     * @param \Brick\Http\Request $request
     *
     * @return RequestResponse
     */
    public function rawRequest(Request $request)
    {
        $cookies = $this->getCookiesForRequest($request);
        $request->setCookies($cookies);

        if ($this->messageListener) {
            $this->messageListener->listen($request);
        }

        $response = $this->requestHandler->handle($request);

        if ($this->messageListener) {
            $this->messageListener->listen($response);
        }

        $this->cookieStore->update($request, $response);

        $this->lastRequest = $request;
        $this->lastResponse = $response;

        if ($this->autoFollowRedirects && $response->isRedirection()) {
            // @todo should only redirect for 301, 302, 303, 307, and 308
            return $this->followRedirect();
        }

        return new RequestResponse($request, $response);
    }

    /**
     * Manually follow a redirection.
     *
     * This is only useful when autoFollowRedirect is false.
     *
     * @return RequestResponse
     *
     * @throws \LogicException If there is no redirection to follow.
     */
    public function followRedirect()
    {
        $response = $this->getLastResponse();

        if (! $response->hasHeader('Location')) {
            throw new \LogicException('There is no redirection to follow.');
        }

        $location = $response->getHeader('Location');
        $location = $this->getAbsoluteUrl($location);

        $request = $this->getLastRequest();

        if ($request->hasHeader('Referer')) {
            $headers = ['Referer' => $request->getHeader('Referer')];
        } else {
            $headers = [];
        }

        return $this->doRequest('GET', $location, $headers, false);
    }

    /**
     * Returns the current URL.
     *
     * @return string
     * @throws \LogicException If no request has been sent yet.
     */
    public function getUrl()
    {
        return $this->getLastRequest()->getUrl();
    }

    /**
     * Returns the last Request sent.
     *
     * @return \Brick\Http\Request The last request sent.
     *
     * @throws \LogicException If no request has been sent yet.
     */
    public function getLastRequest()
    {
        if ($this->lastRequest === null) {
            throw new \LogicException('No request has been sent yet.');
        }

        return $this->lastRequest;
    }

    /**
     * Returns the last Response received.
     *
     * @return \Brick\Http\Response The last response received.
     *
     * @throws \LogicException If no response has been received yet.
     */
    public function getLastResponse()
    {
        if ($this->lastResponse === null) {
            throw new \LogicException('No response has been received yet.');
        }

        return $this->lastResponse;
    }

    /**
     * Sets the headers to send with each request.
     *
     * @param array $headers An associativate array of headers.
     *
     * @return static This HttpClient instance.
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Sets whether to automatically follow redirects.
     *
     * @param bool $follow Whether to automatically follow redirects.
     *
     * @return static This HttpClient instance.
     */
    public function autoFollowRedirects($follow = true)
    {
        $this->autoFollowRedirects = (bool) $follow;

        return $this;
    }

    /**
     * @param string $uri
     * @return string
     */
    public function getAbsoluteUrl($uri)
    {
        $uri = $this->removeFragment($uri);
        $request = $this->getLastRequest();

        // Empty URI
        if ($uri == '') {
            return $request->getUrl();
        }

        // Absolute URL with scheme
        if (preg_match('~^[a-z]+\://~i', $uri) == 1) {
            return $uri;
        }

        // Absolute URL with no scheme
        if (substr($uri, 0, 2) == '//') {
            return $request->getScheme() . ':' . $uri;
        }

        // Absolute URI
        if ($uri[0] == '/') {
            return $request->getUrlBase() . $uri;
        }

        // Relative URI
        $url = $request->getUrl();
        $url = substr($url, 0, strrpos($url, '/') + 1);

        return $url . $uri;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function removeFragment($uri)
    {
        $pos = strpos($uri, '#');

        return is_int($pos) ? substr($uri, 0, $pos) : $uri;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getCookiesForRequest(Request $request)
    {
        return $this->cookieStore->getAsArray(CookieOrigin::createFromRequest($request));
    }
}
