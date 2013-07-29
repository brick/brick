<?php

namespace Brick\Form;

/**
 * Base class for Form & Component.
 */
abstract class Base
{
    /**
     * @var string[]
     */
    private $errors = [];

    /**
     * @param string $errorMessage
     * @return static
     */
    public function addError($errorMessage)
    {
        $this->errors[] = $errorMessage;
        return $this;
    }

    /**
     * @return static
     */
    public function resetErrors()
    {
        $this->errors = [];
        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) != 0;
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
