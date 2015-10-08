<?php

namespace Brick\Translation\Loader;

use Brick\Translation\TranslationLoader;

/**
 * Translation loader that merges dictionaries with fallback locales.
 */
class FallbackLoader implements TranslationLoader
{
    /**
     * @var TranslationLoader
     */
    private $loader;

    /**
     * @var array
     */
    private $extends = [];

    /**
     * @param TranslationLoader $loader The underlying translation loader.
     */
    public function __construct(TranslationLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param array $extends
     *
     * @return void
     */
    public function addExtends(array $extends)
    {
        $this->extends = $extends + $this->extends;
    }

    /**
     * {@inheritdoc}
     */
    public function load($locale)
    {
        $dictionary = $this->loader->load($locale);

        if (isset($this->extends[$locale])) {
            $dictionary += $this->load($this->extends[$locale]);
        }

        return $dictionary;
    }
}
