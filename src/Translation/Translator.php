<?php

namespace Brick\Translation;

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
     * The locale to use if none is given in `translate()`.
     *
     * @var string|null
     */
    private $defaultLocale = null;

    /**
     * An associative array of locale to dictionary.
     *
     * Each dictionary is itself an associative array of keys to texts.
     *
     * @var array
     */
    private $dictionaries = [];

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
     * @param TranslationLoader $loader
     */
    public function __construct(TranslationLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param string|null $defaultLocale The new default locale, or null to remove it.
     *
     * @return void
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string|null
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
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
     * @param string|null $locale     The locale to translate in, or null to use the default locale.
     *
     * @return string
     *
     * @throws \Exception
     */
    public function translate($key, array $parameters = [], $locale = null)
    {
        if ($locale === null) {
            if ($this->defaultLocale === null) {
                throw new \Exception('No default locale has been set.');
            }

            $locale = $this->defaultLocale;
        }

        if (! isset($this->dictionaries[$locale])) {
            $this->dictionaries[$locale] = $this->loader->load($locale);
        }

        $dictionary = $this->dictionaries[$locale];

        if (! isset($dictionary[$key])) {
            return $key;
        }

        $value = $dictionary[$key];

        if ($parameters) {
            return $this->replaceParameters($value, $parameters);
        }

        return $value;
    }

    /**
     * Replaces parameters in a string.
     *
     * This is called internally by `translate()`, but is exposed as a public method to allow
     * more advanced uses such as replacing parameters after executing a transformation on a
     * translated string.
     *
     * @param string $string
     * @param array  $parameters
     *
     * @return string
     */
    public function replaceParameters($string, array $parameters)
    {
        $placeholders = [];

        foreach ($parameters as $key => $value) {
            $key = $this->parameterPrefix . $key . $this->parameterSuffix;
            $placeholders[$key] = $value;
        }

        return strtr($string, $placeholders);
    }
}
