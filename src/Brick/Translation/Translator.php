<?php

namespace Brick\Translation;

use Brick\Locale\Locale;

/**
 * Base implementation of a translator.
 */
class Translator
{
    /**
     * The translation loader.
     *
     * @var \Brick\Translation\TranslationLoader
     */
    private $loader;

    /**
     * The locale to use if none if given in `translate()`.
     *
     * @var \Brick\Locale\Locale
     */
    private $locale;

    /**
     * The fallback locales, used when a key is not found in a given Locale.
     *
     * @var \Brick\Locale\Locale[]
     */
    private $fallbackLocales = [];

    /**
     * An associative array of texts and locales.
     *
     * @var array
     */
    private $texts = [];

    /**
     * An optional string to prepend to parameter keys, such as `[`.
     *
     * @var string
     */
    private $parameterPrefix = '';

    /**
     * An optional string to append to parameter keys, such as `]`.
     *
     * @var string
     */
    private $parameterSuffix = '';

    /**
     * @param \Brick\Translation\TranslationLoader $loader
     */
    public function __construct(TranslationLoader $loader)
    {
        $this->loader = $loader;
        $this->locale = Locale::getDefault();
    }

    /**
     * @param \Brick\Locale\Locale $locale
     *
     * @return void
     */
    public function setLocale(Locale $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return \Brick\Locale\Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param Locale $fallbackLocale
     *
     * @return void
     */
    public function addFallbackLocale(Locale $fallbackLocale)
    {
        $fallbackLocales = $this->computeFallbackLocales($fallbackLocale);
        $fallbackLocales = array_merge($this->fallbackLocales, $fallbackLocales);
        $this->fallbackLocales = array_unique($fallbackLocales);
    }

    /**
     * @param string $prefix The prefix, such as `[`.
     * @param string $suffix The suffix, such as `]`.
     *
     * @return void
     */
    public function setParameterPrefixSuffix($prefix, $suffix)
    {
        $this->parameterPrefix = $prefix;
        $this->parameterSuffix = $suffix;
    }

    /**
     * @param string      $key        The translation key to look up.
     * @param array       $parameters An associative array of parameters to replace in the translated string.
     * @param Locale|null $locale     The locale to translate in, or null to use the default locale.
     *
     * @return string
     */
    public function translate($key, array $parameters = [], Locale $locale = null)
    {
        $locale = $locale ?: $this->locale;

        foreach ($this->computeLookupLocales($locale) as $locale) {
            $result = $this->translateIn($key, $locale);
            if ($result !== null) {
                $placeholders = [];

                foreach ($parameters as $key => $value) {
                    $key = $this->parameterPrefix . $key . $this->parameterSuffix;
                    $placeholders[$key] = $value;
                }

                return strtr($result, $placeholders);
            }
        }

        return $key;
    }

    /**
     * Computes the Locales to lookup, including the fallbacks, for a given Locale.
     *
     * @param Locale $locale
     *
     * @return Locale[]
     */
    private function computeLookupLocales(Locale $locale)
    {
        $locales = $this->computeFallbackLocales($locale);
        $locales = array_merge($locales, $this->fallbackLocales);

        return array_unique($locales);
    }

    /**
     * @param Locale $locale
     *
     * @return Locale[]
     */
    private function computeFallbackLocales(Locale $locale)
    {
        $locales = [$locale];

        if (! $this->isLocaleLanguageOnly($locale)) {
            // Always fallback to the primary language.
            $locales[] = $this->localeToLanguageOnly($locale);
        }

        return $locales;
    }

    /**
     * @param Locale $locale
     *
     * @return boolean
     */
    private function isLocaleLanguageOnly(Locale $locale)
    {
        return preg_match('/[^a-zA-Z]/', $locale->toString()) == 0;
    }

    /**
     * @param Locale $locale
     *
     * @return Locale
     */
    private function localeToLanguageOnly(Locale $locale)
    {
        return Locale::create($locale->getLanguage());
    }

    /**
     * @param string $key
     *
     * @param Locale $locale
     *
     * @return string|null
     */
    private function translateIn($key, Locale $locale)
    {
        $localeString = $locale->toString();

        if (! isset($this->texts[$localeString])) {
            $this->texts[$localeString] = $this->loader->load($locale);
        }

        $texts = $this->texts[$localeString];

        return isset($texts[$key]) ? $texts[$key] : null;
    }
}
