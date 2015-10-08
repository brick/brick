<?php

namespace Brick\Translation;

/**
 * Interface that translation loaders must implement.
 */
interface TranslationLoader
{
    /**
     * Loads the available translations in a given locale.
     *
     * If no translations are available in the given locale,
     * or if the given locale is not supported,
     * this method must return an empty array.
     *
     * @param string $locale The locale in which to load the translations.
     *
     * @return array An associative array mapping translation keys to translated texts.
     */
    public function load($locale);
}
