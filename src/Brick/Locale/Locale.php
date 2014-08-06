<?php

namespace Brick\Locale;

use Brick\Type\Cast;

/**
 * Instantiable Locale class.
 */
class Locale
{
    /**
     * The canonicalized locale as a string.
     *
     * @var string
     */
    private $locale;

    /**
     * The individual, canonicalized subtags as an associative array.
     *
     * @var array
     */
    private $subtags;

    /**
     * Private constructor.
     *
     * @param string $locale
     * @param array  $subtags
     */
    private function __construct($locale, array $subtags)
    {
        $this->locale = $locale;
        $this->subtags = $subtags;
    }

    /**
     * @param array $subtags
     *
     * @return Locale
     *
     * @throws \InvalidArgumentException
     */
    private static function build(array $subtags)
    {
        if (! isset($subtags['language'])) {
            throw new \InvalidArgumentException('Invalid locale.');
        }

        if (preg_match('/^[a-zA-Z]{2,8}$/', $subtags['language']) == 0) {
            throw new \InvalidArgumentException('Invalid language subtag.');
        }

        if (isset($subtags['script'])) {
            if (preg_match('/^[a-zA-Z]{4}$/', $subtags['script']) == 0) {
                throw new \InvalidArgumentException('Invalid script subtag.');
            }
        }

        if (isset($subtags['region'])) {
            if (preg_match('/^[a-zA-Z]{2}|[0-9]{3}$/', $subtags['region']) == 0) {
                throw new \InvalidArgumentException('Invalid region subtag.');
            }
        }

        for ($i = 0; isset($subtags[$key = 'variant' . $i]); $i++) {
            if (preg_match('/^[0-9][0-9a-zA-Z]{3}|[0-9a-zA-Z]{5,8}$/', $subtags[$key]) == 0) {
                throw new \InvalidArgumentException('Invalid variant subtag.');
            }
        }

        $locale = \Locale::composeLocale($subtags);

        // Another round trip to canonicalize the locale with parseLocale().
        $subtags = \Locale::parseLocale($locale);
        $locale = \Locale::composeLocale($subtags);

        return new Locale($locale, $subtags);
    }

    /**
     * Creates a locale from a language and an optional region.
     *
     * @param string      $language
     * @param string|null $region
     *
     * @return Locale
     *
     * @throws \InvalidArgumentException
     */
    public static function create($language, $region = null)
    {
        $subtags = [
            'language' => (string) $language
        ];

        if ($region !== null) {
            $subtags['region'] = (string) $region;
        }

        return self::build($subtags);
    }

    /**
     * Parses a locale string.
     *
     * @param string $locale
     *
     * @return Locale
     *
     * @throws \InvalidArgumentException
     */
    public static function parse($locale)
    {
        $locale = (string) $locale;

        if ($locale === '') {
            throw new \InvalidArgumentException('Locale string cannot be empty.');
        }

        $subtags = \Locale::parseLocale($locale);

        return self::build($subtags);
    }

    /**
     * Returns the default locale.
     *
     * @return Locale
     */
    public static function getDefault()
    {
        return Locale::parse(\Locale::getDefault());
    }

    /**
     * Sets the default locale.
     *
     * @param Locale $locale
     *
     * @return void
     */
    public static function setDefault(Locale $locale)
    {
        \Locale::setDefault($locale->locale);
    }

    /**
     * Returns the language subtag for this locale.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->subtags['language'];
    }

    /**
     * Returns the script subtag for this locale, or null if there is no script.
     *
     * @return string|null
     */
    public function getScript()
    {
        return isset($this->subtags['script']) ? $this->subtags['script'] : null;
    }

    /**
     * Returns the region subtag for this locale, or null if there is no region.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return isset($this->subtags['region']) ? $this->subtags['region'] : null;
    }

    /**
     * Returns the variant subtags for this locale as an array.
     *
     * @return array
     */
    public function getVariants()
    {
        $variants = [];

        for ($i = 0; isset($this->subtags[$key = 'variant' . $i]); $i++) {
            $variants[] = $this->subtags[$key];
        }

        return $variants;
    }

    /**
     * Returns a name for the locale that is appropriate for display to the user.
     *
     * @param Locale|null $inLocale
     *
     * @return string
     */
    public function getDisplayName(Locale $inLocale = null)
    {
        return \Locale::getDisplayName($this->locale, $inLocale ? $inLocale->locale : null);
    }

    /**
     * Returns a name for the locale's language that is appropriate for display to the user.
     *
     * @param Locale|null $inLocale
     *
     * @return string
     */
    public function getDisplayLanguage(Locale $inLocale = null)
    {
        return \Locale::getDisplayLanguage($this->locale, $inLocale ? $inLocale->locale : null);
    }

    /**
     * Returns a name for the the locale's script that is appropriate for display to the user.
     *
     * @param Locale|null $inLocale
     *
     * @return string
     */
    public function getDisplayScript(Locale $inLocale = null)
    {
        return \Locale::getDisplayScript($this->locale, $inLocale ? $inLocale->locale : null);
    }

    /**
     * Returns a name for the locale's region that is appropriate for display to the user.
     *
     * @param Locale|null $inLocale
     *
     * @return string
     */
    public function getDisplayRegion(Locale $inLocale = null)
    {
        return \Locale::getDisplayRegion($this->locale, $inLocale ? $inLocale->locale : null);
    }

    /**
     * Returns a name for the locale's variant code that is appropriate for display to the user.
     *
     * @param Locale|null $inLocale
     *
     * @return string
     */
    public function getDisplayVariant(Locale $inLocale = null)
    {
        return \Locale::getDisplayVariant($this->locale, $inLocale ? $inLocale->locale : null);
    }

    /**
     * Compares this locale to another.
     *
     * @param Locale $locale
     *
     * @return boolean
     */
    public function isEqualTo(Locale $locale)
    {
        return $this->locale == $locale->locale;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->locale;
    }

    /**
     * Returns a well-formed IETF BCP 47 language tag representing this locale.
     *
     * @return string
     */
    public function toLanguageTag()
    {
        return str_replace('_', '-', $this->locale);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->locale;
    }
}
