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
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $currency;

    /**
     * @param string      $code
     * @param string      $name
     * @param string|null $currency
     */
    private function __construct($code, $name, $currency)
    {
        $this->code = $code;
        $this->name = $name;
        $this->currency = $currency;
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

        $countries = array();

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
    public function hasCurrrency()
    {
        return $this->currency !== null;
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
        if ($this->currency === null) {
            throw new \LogicException('This country has no currency.');
        }

        return Currency::getInstance($this->currency);
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
