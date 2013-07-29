<?php

namespace Brick\Controller\Annotation;

/**
 * This annotation restricts the HTTP methods allowed on a given controller action.
 *
 * @Annotation
 * @Target("METHOD")
 */
class Allow extends AbstractAnnotation
{
    /**
     * The HTTP method(s) the controller action accepts.
     *
     * @var array
     */
    private $methods = [];

    /**
     * @param string|array $methods
     */
    public function setValue($methods)
    {
        $this->methods = is_array($methods) ? $methods : array($methods);
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
