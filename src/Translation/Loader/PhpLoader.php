<?php

namespace Brick\Translation\Loader;

use Brick\Translation\TranslationLoader;

/**
 * Loads translations stored in PHP files.
 */
class PhpLoader implements TranslationLoader
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $directory
     * @param string $pattern
     */
    public function __construct($directory, $pattern = '%s.php')
    {
        $this->directory = $directory;
        $this->pattern   = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function load($locale)
    {
        $file = $this->directory . DIRECTORY_SEPARATOR . sprintf($this->pattern, $locale);

        if (file_exists($file)) {
            return require $file;
        }

        return [];
    }
}
