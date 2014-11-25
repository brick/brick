<?php

namespace Brick\Locale;

/**
 * A language as defined by ISO 639-1 and ISO 639-2.
 */
class Language
{
    /**
     * @var array|null
     */
    private static $languages;

    /**
     * @var array|null
     */
    private static $iso2to3;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * @var string
     */
    private $alpha3B;

    /**
     * @var string|null
     */
    private $alpha3T;

    /**
     * @var string|null
     */
    private $alpha2;

    /**
     * @var string
     */
    private $englishName;

    /**
     * @var string
     */
    private $frenchName;

    /**
     * Private constructor. Use get() to obtain an instance.
     *
     * @param string      $alpha3B
     * @param string|null $alpha3T
     * @param string|null $alpha2
     * @param string      $englishName
     * @param string      $frenchName
     */
    private function __construct($alpha3B, $alpha3T, $alpha2, $englishName, $frenchName)
    {
        $this->alpha3B     = $alpha3B;
        $this->alpha3T     = $alpha3T;
        $this->alpha2      = $alpha2;
        $this->englishName = $englishName;
        $this->frenchName  = $frenchName;
    }

    /**
     * @return void
     */
    private static function loadLanguageData()
    {
        if (self::$languages === null) {
            self::$languages = require __DIR__ . '/languages.php';
            self::$iso2to3   = require __DIR__ . '/languages-alpha-2-to-3.php';
        }
    }

    /**
     * Returns a Language for the given language code.
     *
     * The language code can be either:
     * * ISO 639-1 (2 lowercase letters)
     * * ISO 639-2/B (3 lowercase letters)
     * * ISO 639-2/T (3 lowercase letters)
     *
     * @param string $code An ISO 639 language code.
     *
     * @return Language
     *
     * @throws \LogicException
     */
    public static function get($code)
    {
        self::loadLanguageData();

        if (isset(self::$iso2to3[$code])) {
            $code = self::$iso2to3[$code];
        }

        if (! isset(self::$instances[$code])) {
            if (! isset(self::$languages[$code])) {
                throw new \RuntimeException('Invalid language code: ' . $code);
            }

            list ($alpha3T, $alpha2, $englishName, $frenchName) = self::$languages[$code];

            self::$instances[$code] = new self($code, $alpha3T, $alpha2, $englishName, $frenchName);
        }

        return self::$instances[$code];
    }

    /**
     * @return string
     */
    public function getAlpha3BCode()
    {
        return $this->alpha3B;
    }

    /**
     * @return string|null
     */
    public function getAlpha3TCode()
    {
        return $this->alpha3T;
    }

    /**
     * @return string|null
     */
    public function getAlpha2Code()
    {
        return $this->alpha2;
    }

    /**
     * @return string
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }

    /**
     * @return string
     */
    public function getFrenchName()
    {
        return $this->frenchName;
    }
}
