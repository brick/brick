<?php

declare(strict_types=1);

namespace Brick\Locale;

/**
 * A country as defined by ISO 3166.
 */
class Country
{
    /**
     * @var array|null
     */
    private static $countries;

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
    private function __construct(string $code, string $name, ?string $currencyCode)
    {
        $this->code         = $code;
        $this->name         = $name;
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return void
     */
    private static function loadCountryData() : void
    {
        if (self::$countries === null) {
            self::$countries = require __DIR__ . '/countries.php';
        }
    }

    /**
     * Returns the country matching the given country code.
     *
     * @param string $code
     *
     * @return Country
     *
     * @throws \RuntimeException If no country exists for this country code.
     */
    public static function of(string $code) : Country
    {
        if (! isset(self::$instances[$code])) {
            self::loadCountryData();

            if (! isset(self::$countries[$code])) {
                throw new \RuntimeException('Invalid country code: ' . $code);
            }

            [$code, $name, $currency] = self::$countries[$code];

            self::$instances[$code] = new self($code, $name, $currency);
        }

        return self::$instances[$code];
    }

    /**
     * Returns all the available countries.
     *
     * @return Country[]
     */
    public static function getAvailableCountries() : array
    {
        self::loadCountryData();

        $countries = [];

        foreach (self::$countries as $countryCode => $data) {
            $countries[] = self::of($countryCode);
        }

        return $countries;
    }

    /**
     * Returns the two-letter ISO 3166 country code.
     *
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }

    /**
     * Returns the UTF-8 capitalized country name as present in ISO 3166.
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getCurrencyCode() : ?string
    {
        return $this->currencyCode;
    }

    /**
     * @param Country $country
     *
     * @return bool
     */
    public function isEqualTo(Country $country) : bool
    {
        return $this->code === $country->code;
    }
}
