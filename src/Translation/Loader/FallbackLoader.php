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
     * @var string|null
     */
    private $fallbackLocale;

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
     * Sets the locale all locales fall back to when a string is untranslated.
     *
     * @param string $locale
     *
     * @return void
     */
    public function setFallbackLocale($locale)
    {
        $this->fallbackLocale = $locale;
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

        if ($this->fallbackLocale !== null && $locale !== $this->fallbackLocale) {
            $dictionary += $this->load($this->fallbackLocale);
        }

        return $dictionary;
    }
}
