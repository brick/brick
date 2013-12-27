<?php

namespace Brick;

/**
 * A transient error handler to catch PHP errors in a specific code block.
 */
class ErrorHandler
{
    /**
     * @var callable
     */
    private $transientErrorHandler;

    /**
     * @var callable|null
     */
    private $errorExceptionHandler;

    /**
     * @var callable|null
     */
    private $previousErrorHandler;

    /**
     * @var integer|null
     */
    private $severity;

    /**
     * @param callable|null $handler A function that will receive the ErrorException as a parameter if an error occurs.
     */
    public function __construct(callable $handler = null)
    {
        $this->errorExceptionHandler = $handler;

        $this->transientErrorHandler = function($level, $message, $file, $line, $context) {
            if ($this->severity & $level) {
                call_user_func($this->errorExceptionHandler, new \ErrorException($message, 0, $level, $file, $line));
            } elseif ($this->previousErrorHandler) {
                call_user_func($this->previousErrorHandler, $level, $message, $file, $line, $context);
            }
        };
    }

    /**
     * @param integer  $severity
     * @param callable $function
     * @param array    $parameters
     *
     * @return mixed
     */
    public function swallow($severity, callable $function, array $parameters = [])
    {
        $this->severity = $severity;
        $this->previousErrorHandler = set_error_handler($this->transientErrorHandler);

        $result = call_user_func_array($function, $parameters);
        restore_error_handler();

        return $result;
    }
}
