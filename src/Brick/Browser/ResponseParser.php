<?php

namespace Brick\Browser;

use Brick\Http\Response;
use Brick\Json\Json;

/**
 * Wraps a Response and provides tools to process it.
 */
class ResponseParser
{
    /**
     * @var \Brick\Http\Response
     */
    private $response;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the wrapped Response object.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->response->getContent();
    }

    /**
     * Parses the response as an XML / HTML document.
     *
     * If the response is not a valid document, and empty document is returned.
     *
     * @return \DOMDocument
     */
    public function parseDocument()
    {
        $pattern ='/(x|ht)ml(?:.*;charset=(\S+))?/i';
        $contentType = $this->response->getFirstHeader('Content-Type');
        $isDocument = (preg_match($pattern, $contentType, $matches) == 1);
        $charset = (isset($matches[2]) ? $matches[2] : 'UTF-8');

        $useInternalErrors = libxml_use_internal_errors(true);
        $disableEntityLoader = libxml_disable_entity_loader(true);

        $document = new \DOMDocument('1.0', $charset);
        $document->validateOnParse = true;

        if ($isDocument) {
            $isXml = (strtolower($matches[1]) == 'x');

            if ($isXml) {
                $document->loadXML($this->getText());
            } else {
                $document->loadHTML($this->getText());
            }
        }

        libxml_use_internal_errors($useInternalErrors);
        libxml_disable_entity_loader($disableEntityLoader);

        return $document;
    }

    /**
     * Parses the response as JSON.
     *
     * @param bool $assoc
     *
     * @return mixed
     *
     * @throws \Brick\Json\JsonException
     */
    public function parseJson($assoc = false)
    {
        return Json::decode($this->getText(), $assoc);
    }
}
