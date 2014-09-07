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
     * HTML-escapes a text string.
     *
     * This is the single most important protection againts XSS attacks:
     * any user-originated data, or more generally any data that is not known to be valid and trusted HTML,
     * must be escaped before being displayed in a web page.
     *
     * @param string  $text       The text to escape.
     * @param boolean $lineBreaks Whether to escape line breaks. Defaults to `false`.
     *
     * @return string
     */
    public function html($text, $lineBreaks = false)
    {
        $html = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        return $lineBreaks ? nl2br($html) : $html;
    }
}
