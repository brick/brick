<?php

namespace Brick\Controller\ParameterConverter;

/**
 * Returns request parameters as is, without conversion.
 */
class NullConverter implements ParameterConverter
{
    /**
     * {@inheritdoc}
     */
    public function convertParameter(\ReflectionParameter $parameter, $value)
    {
        return $value;
    }
}
