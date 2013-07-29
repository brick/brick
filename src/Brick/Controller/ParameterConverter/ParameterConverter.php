<?php

namespace Brick\Controller\ParameterConverter;

/**
 * Converts request parameters before injecting them in the controller.
 */
interface ParameterConverter
{
    /**
     * @param \ReflectionParameter $parameter The reflection of the controller parameter being resolved.
     * @param string|array         $value     The raw request parameter value.
     *
     * @return mixed The converted value.
     */
    public function convertParameter(\ReflectionParameter $parameter, $value);
}
