<?php

namespace Brick\View;

/**
 * Abstract base class to work with .phtml view scripts.
 */
abstract class AbstractView implements View
{
    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->capture(function() {
            require $this->getScriptPath();
        });
    }

    /**
     * Captures all output from the given function, and returns it.
     *
     * @param callable $function The function to execute. Must not have arguments.
     *
     * @return string
     */
    public function capture(callable $function)
    {
        ob_start();

        try {
            $function();
        } finally {
            $content = ob_get_clean();
        }

        return $content;
    }

    /**
     * Returns the view script path.
     *
     * Defaults to the class file path, with a .phtml extension, but can be overridden by child classes.
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getScriptPath()
    {
        $class = new \ReflectionClass($this->getClassName());
        $path = $class->getFileName();
        $path = preg_replace('/\.php$/', '.phtml', $path, -1, $count);

        if ($count != 1) {
            throw new \RuntimeException('The class filename does not end with .php');
        }

        return $path;
    }

    /**
     * Returns the view class to use to detect the script path.
     *
     * Defaults to the called class, but can be overridden by child classes.
     *
     * @return string
     */
    protected function getClassName()
    {
        return get_called_class();
    }

    /**
     * Escapes a string for HTML output.
     *
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Escapes a string for HTML output, and converts line breaks.
     *
     * @param string $string
     * @return string
     */
    public function escapeWithLineBreaks($string)
    {
        return nl2br($this->escape($string));
    }
}
