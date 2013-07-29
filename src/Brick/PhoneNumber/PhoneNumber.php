<?php

namespace Brick\PhoneNumber;

/**
 * An international phone number, as defined by ITU-T recommendation E.164.
 *
 * This class is immutable.
 */
class PhoneNumber
{
    /**
     * The utility object that accesses phone number data.
     * A single instance exists accross all PhoneNumber objects.
     *
     * @var PhoneNumberUtil|null
     */
    private static $phoneNumberUtil = null;

    /**
     * The E.164 country code.
     *
     * @var string
     */
    private $countryCode;

    /**
     * The national phone number.
     *
     * @var string
     */
    private $nationalNumber;

    /**
     * Private constructor. Use parse() to get an instance.
     *
     * @param string $countryCode
     * @param string $nationalNumber
     */
    private function __construct($countryCode, $nationalNumber)
    {
        $this->countryCode = $countryCode;
        $this->nationalNumber = $nationalNumber;
    }

    /**
     * Returns an instance of PhoneNumberUtil shared by all instances of PhoneNumber.
     *
     * @return PhoneNumberUtil
     */
    private static function getPhoneNumberUtil()
    {
        if (self::$phoneNumberUtil === null) {
            self::$phoneNumberUtil = new PhoneNumberUtil();
        }

        return self::$phoneNumberUtil;
    }

    /**
     * @param string      $phoneNumber The phone number to parse.
     * @param string|null $regionCode  The region code to assume, if the number is not in international format.
     *
     * @return PhoneNumber
     *
     * @throws PhoneNumberParseException
     */
    public static function parse($phoneNumber, $regionCode = null)
    {
        list ($countryCode, $nationalNumber) = self::getPhoneNumberUtil()->parse($phoneNumber, $regionCode);

        return new self($countryCode, $nationalNumber);
    }

    /**
     * Returns the country code of this PhoneNumber.
     *
     * The country code is a series of 1 to 3 digits, as defined per the E.164 recommendation.
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Returns the national number of this PhoneNumber.
     *
     * The national number is a series of digits.
     *
     * @return string
     */
    public function getNationalNumber()
    {
        return $this->nationalNumber;
    }

    /**
     * Returns the region code of this PhoneNumber.
     *
     * The region code is an ISO 3166-1 alpha-2 country code.
     * If the phone number does not map to a geographic region
     * (global networks, such as satellite phone numbers) this method returns null.
     *
     * @return string|null The region code, or null if the number does not map to a geographic region.
     */
    public function getRegionCode()
    {
        return self::getPhoneNumberUtil()->getRegionCodeForNumber($this);
    }

    /**
     * @return boolean
     */
    public function isValidNumber()
    {
        return self::getPhoneNumberUtil()->isValidNumber($this);
    }

    /**
     * @return string
     */
    public function getNumberType()
    {
        return self::getPhoneNumberUtil()->getNumberType($this);
    }

    /**
     * @param string $numberFormat
     * @return string
     */
    public function format($numberFormat)
    {
        return self::getPhoneNumberUtil()->format($this, $numberFormat);
    }

    /**
     * @param bool $leadingPlus
     *
     * @return string
     */
    public function toString($leadingPlus = true)
    {
        return ($leadingPlus ? '+' : '') . $this->countryCode . $this->nationalNumber;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
