<?php

namespace Brick\Application\Controller;

use Brick\View\View;
use Brick\View\ViewRenderer;
use Brick\Http\Response;
use Brick\Di\Annotation\Inject;

/**
 * Base controller class with helper methods for common cases.
 */
abstract class AbstractController
{
    /**
     * @var \Brick\View\ViewRenderer
     */
    private $renderer;

    /**
     * @Inject
     *
     * @param \Brick\View\ViewRenderer $renderer
     */
    public function injectViewRenderer(ViewRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param \Brick\View\View $view
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function renderAsString(View $view)
    {
        if ($this->renderer === null) {
            throw new \RuntimeException('No view renderer has been injected');
        }

        return $this->renderer->render($view);
    }

    /**
     * Renders a View in a Response object.
     *
     * @param \Brick\View\View $view
     *
     * @return \Brick\Http\Response
     */
    protected function render(View $view)
    {
        return $this->renderHtml($this->renderAsString($view));
    }

    /**
     * @param string $text
     *
     * @return \Brick\Http\Response
     */
    protected function renderText($text)
    {
        return $this->createResponse($text, 'text/plain');
    }

    /**
     * @param string $html
     *
     * @return \Brick\Http\Response
     */
    protected function renderHtml($html)
    {
        return $this->createResponse($html, 'text/html');
    }

    /**
     * @param mixed $data
     *
     * @return \Brick\Http\Response
     */
    protected function renderJson($data)
    {
        return $this->createResponse(json_encode($data, JSON_PRETTY_PRINT), 'application/json');
    }

    /**
     * @param string $data
     * @param string $contentType
     *
     * @return Response
     */
    private function createResponse($data, $contentType)
    {
        return (new Response())
            ->setContent($data)
            ->setHeader('Content-Type', $contentType);
    }

    /**
     * @param string  $uri
     * @param integer $statusCode
     *
     * @return \Brick\Http\Response
     */
    protected function redirect($uri, $statusCode = 302)
    {
        return (new Response())
            ->setStatusCode($statusCode)
            ->setHeader('Location', $uri);
    }
}
