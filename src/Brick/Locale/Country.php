<?php

namespace Brick\Locale;

/**
 * A country as defined by ISO 3166.
 */
class Country
{
    /**
     * @var array|null
     */
    private static $countries = null;

    /**
     * @var array
     */
    private static $instances = [];

    /**
     * The ISO 3166-1 alpha-2 two-letter country code.
     *
     * @var string
     */
    private $code;

    /**
     * The country name as defined by ISO 3166-1.
     *
     * @var string
     */
    private $name;

    /**
     * The main currency code of the country, or null if the country has no official currency.
     *
     * @var string|null
     */
    private $currencyCode;

    /**
     * @param string      $code         The ISO 3166-1 alpha-2 country code.
     * @param string      $name         The ISO 3166-1 country name.
     * @param string|null $currencyCode The currency code, or null if the country has no currency.
     */
    private function __construct($code, $name, $currencyCode)
    {
        $this->code     = $code;
        $this->name     = $name;
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return void
     */
    private static function loadCountryData()
    {
        if (self::$countries === null) {
            self::$countries = require __DIR__ . '/countries.php';
        }
    }

    /**
     * @param string $code
     * @return Country
     * @throws \RuntimeException
     */
    public static function getInstance($code)
    {
        if (! isset(self::$instances[$code])) {
            self::loadCountryData();

            if (! isset(self::$countries[$code])) {
                throw new \RuntimeException('Invalid country code: ' . $code);
            }

            list ($code, $name, $currency) = self::$countries[$code];

            self::$instances[$code] = new self($code, $name, $currency);
        }

        return self::$instances[$code];
    }

    /**
     * Returns all the available countries.
     *
     * @return Country[]
     */
    public static function getAvailableCountries()
    {
        self::loadCountryData();

        $countries = [];

        foreach (self::$countries as $countryCode => $data) {
            $countries[] = self::getInstance($countryCode);
        }

        return $countries;
    }

    /**
     * Returns the two-letter ISO 3166 country code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the UTF-8 capitalized country name as present in ISO 3166.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function hasCurrency()
    {
        return $this->currencyCode !== null;
    }

    /**
     * Returns the Currency instance for this country.
     *
     * Some countries may have several currencies in use, in this case only the main currency is returned.
     * In countries such as Cuba or Panama, this might not be the one you expect.
     *
     * @return \Brick\Locale\Currency
     * @throws \LogicException
     */
    public function getCurrency()
    {
        if ($this->currencyCode === null) {
            throw new \LogicException('This country has no currency.');
        }

        return Currency::of($this->currencyCode);
    }

    /**
     * @param Country $country
     * @return boolean
     */
    public function isEqualTo(Country $country)
    {
        return $this->code === $country->code;
    }
}
