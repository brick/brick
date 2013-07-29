<?php

namespace Brick\Controller\Annotation;

use Brick\Http\Request;

/**
 * Base class for QueryParam and PostParam.
 */
abstract class RequestParam
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $bindTo;

    /**
     * Class constructor.
     *
     * @param array $values
     * @throws \RuntimeException
     */
    public function __construct(array $values)
    {
        if (isset($values['name'])) {
            $name = $values['name'];
        } elseif (isset($values['value'])) {
            $name = $values['value'];
        } else {
            $r = new \ReflectionObject($this);
            $message = '@' . $r->getShortName() . ' requires a parameter name';
            throw new \RuntimeException($message);
        }

        $this->name = $name;
        $this->bindTo = isset($values['bindTo']) ? $values['bindTo'] : $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBindTo()
    {
        return $this->bindTo;
    }

    /**
     * Returns the request parameter type: query, post, ...
     *
     * @return string
     */
    abstract public function getParameterType();

    /**
     * Returns the relevant ParameterMap from the request.
     *
     * @param \Brick\Http\Request $request
     *
     * @return array
     */
    abstract public function getRequestParameters(Request $request);
}
