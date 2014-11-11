<?php

namespace Brick\Locale;

/**
 * A language as defined by ISO 639-1 and ISO 639-2.
 */
class Language
{
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

    }

    public function getAlpha2Code()
    {

    }

    public function getAlpha3Code()
    {
        
    }
}
