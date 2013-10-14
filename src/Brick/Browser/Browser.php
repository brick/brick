<?php

namespace Brick\Browser;

use Brick\Browser\Exception\UnexpectedElementException;
use Brick\Http\Client\Client;
use Brick\Http\Request;
use Brick\Http\Response;
use Brick\Http\Server\RequestHandler;

/**
 * A simple browser.
 */
class Browser extends SearchContext
{
    /**
     * The HttpClient to execute requests.
     *
     * @var \Brick\Http\Client\Client
     */
    private $httpClient;

    /**
     * The browser's history of requests, excluding redirected requests.
     *
     * @var \Brick\Browser\History
     */
    private $history;

    /**
     * The current DOM document, or null if no page has been loaded yet.
     *
     * @var \DOMDocument|null
     */
    private $document = null;

    /**
     * The last response that generated the current document (hence excluding AJAX responses).
     *
     * @var \Brick\Http\Response|null
     */
    private $documentResponse = null;

    /**
     * Class constructor.
     *
     * @param \Brick\Http\Server\RequestHandler $handler A handler to serve the requests.
     */
    public function __construct(RequestHandler $handler)
    {
        $this->httpClient = new Client($handler);
        $this->httpClient->setHeaders([
            'User-Agent' => 'Brick/Browser'
        ]);

        $this->history = new History();
    }

    /**
     * Executes the given request.
     *
     * @param Request $request
     * @param boolean $storeHistory
     * @param boolean $createDocument
     *
     * @return Response
     */
    private function request(Request $request, $storeHistory, $createDocument)
    {
        $requestResponse = $this->httpClient->rawRequest($request);

        $request = $requestResponse->getRequest();
        $response = $requestResponse->getResponse();

        if ($createDocument) {
            $parser = new ResponseParser($response);
            $this->document = $parser->parseDocument();
            $this->documentResponse = $response;
        }

        if ($storeHistory) {
            $this->history->add($request);
        }

        return $response;
    }

    /**
     * Returns the last Response object received by the browser, excluding AJAX responses.
     *
     * @return Response
     *
     * @throws Exception\BrowserException
     */
    public function getLastResponse()
    {
        if ($this->documentResponse === null) {
            throw new Exception\BrowserException('No response has been received yet');
        }

        return $this->documentResponse;
    }

    /**
     * Returns the current browser URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->history->current()->getUrl();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->getLastResponse()->getContent();
    }

    /**
     * @param string $url
     *
     * @return static
     */
    public function open($url)
    {
        $request = $this->httpClient->createRequest('GET', $url);

        $this->request($request, true, true);

        return $this;
    }

    /**
     * Clicks a link or a submit button.
     *
     * This method always changes the current page.
     * If the click does not cause the browser to change page, an exception is thrown.
     *
     * @param Target $target
     *
     * @return static
     *
     * @throws \InvalidArgumentException                If the argument is not a supported object.
     * @throws Exception\UnexpectedElementException     If the element is not clickable.
     * @throws Exception\StaleElementReferenceException If the element does not belong to the current page.
     */
    public function click(Target $target)
    {
        $element = $target->getTargetElement($this);

        if ($element->getTagName() == 'a') {
            // @todo Referer
            return $this->open($this->httpClient->getAbsoluteUrl($element->getAttribute('href')));
        }

        if ($element->getTagName() == 'input' || $element->getTagName() == 'button') {
            return $this->submit($element);
        }

        throw new Exception\UnexpectedElementException('Cannot click on the given element');
    }

    /**
     * Submits a form.
     *
     * If called on a form, submits it.
     * If called on any form element, submits its form.
     * If called on any other element, throws an exception.
     *
     * The element must contain exactly one node, else this method will throw an exception.
     * The element must belong to the current document, else this method will throw an exception.
     *
     * @param Target $target
     *
     * @return static
     *
     * @throws Exception\BrowserException
     * @throws Exception\StaleElementReferenceException If the element does not belong to the current page.
     */
    public function submit(Target $target)
    {
        $element = $target->getTargetElement($this);

        if ($element->is('form')) {
            return $this->submitForm(Wrapper\Form::create($element));
        }

        return $this->submit($element->getParent('form'));

        if ($element instanceof Wrapper\FormControl) {
            if (! $form = $element->getForm()) {
                throw new UnexpectedElementException('The element has no form and cannot be submitted');
            }

            return $this->submit($form);
        }

        throw new Exception\BrowserException('Cannot submit the given element');
    }

    /**
     * @param Wrapper\Form $form
     *
     * @return static
     */
    private function submitForm(Wrapper\Form $form)
    {
        $url = $this->httpClient->getAbsoluteUrl($form->getAction());

        if ($form->isPost()) {
            parse_str($form->getRawData(), $post);
        } else {
            $url = $this->removeQueryString($url) . '?' . $form->getRawData();
            $post = array();
        }

        $headers = array('Referer' => $this->getUrl());
        $request = $this->httpClient->createRequest($form->getMethod(), $url, $post, [], $headers);

        $this->request($request, true, true);

        return $this;
    }

    /**
     * Goes one step back in history.
     *
     * @return static
     *
     * @throws \LogicException If the history is empty, or already on the first page.
     */
    public function back()
    {
        $request = $this->history->back();

        $this->request($request, false, true);

        return $this;
    }

    /**
     * Goes one step forward in history.
     *
     * @return static
     *
     * @throws \LogicException If the history is empty, or already on the last page.
     */
    public function forward()
    {
        $request = $this->history->forward();

        $this->request($request, false, true);

        return $this;
    }

    /**
     * Reloads the current page.
     *
     * @return static
     *
     * @throws \LogicException If the history is empty.
     */
    public function reload()
    {
        $request = $this->history->current();

        $this->request($request, false, true);

        return $this;
    }

    /**
     * Performs an XMLHttpRequest on the server.
     *
     * This is useful to interact with the server as if the requests were made from Javascript.
     *
     * @param string                   $method  The HTTP method.
     * @param string                   $url     The URL to send the request to.
     * @param string|array|object|null $data    The data to send to the server (optional).
     *                                          If the data is an array or an object, it is converted to a query string.
     *                                          If the method is GET, the data will be appended to the URL.
     *                                          If the method is not GET, the data will be sent in the message-body.
     * @param array                    $headers An associative array of headers to send with the request (optional).
     *
     * @return ResponseParser
     */
    public function ajax($method, $url, $data = null, array $headers = [])
    {
        if (is_array($data) || is_object($data)) {
            $data = http_build_query($data);
        }

        if (strtoupper($method) == 'GET') {
            $url = $this->mergeQueryParameters($url, $data);
        }

        if (strtoupper($method) == 'POST') {
            parse_str($data, $post);
        } else {
            $post = [];
        }

        $request = $this->httpClient->createRequest($method, $url, $post, [], $headers);
        $response = $this->request($request, false, false);

        return new ResponseParser($response);
    }

    /**
     * @param string $url
     * @param string $data
     *
     * @return string
     */
    private function mergeQueryParameters($url, $data)
    {
        if (strpos($url, '?') === false) {
            return $url . '?' . $data;
        }

        if ($url[strlen($url) - 1] == '&') {
            return $url . $data;
        }

        return $url . '&' . $data;
    }

    /**
     * @param string $uri
     * @return string
     */
    private function removeQueryString($uri)
    {
        $pos = strpos($uri, '?');

        return is_int($pos) ? substr($uri, 0, $pos) : $uri;
    }

    /**
     * {@inheritdoc}
     */
    protected function getElements()
    {
        if ($this->document === null) {
            throw Exception\BrowserException::noDocumentLoaded();
        }

        $elements = [];

        foreach ($this->document->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                $elements[] = $node;
            }
        }

        return $elements;
    }
}
