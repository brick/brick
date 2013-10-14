<?php

namespace Brick\PhoneNumber;

use Brick\PhoneNumber\Metadata\Territory;
use Brick\PhoneNumber\Metadata\NumberPattern;

/**
 * Utility class for phone numbers.
 */
class PhoneNumberUtil
{
    const MIN_LENGTH = 6;
    const MAX_LENGTH = 15;

    /**
     * The FIRST_GROUP_PATTERN was originally set to $1 but there are some countries for which the
     * first group is not used in the national pattern (e.g. Argentina) so the $1 group does not match
     * correctly.  Therefore, we use \d, so that the first group actually used in the pattern will be
     * matched.
     */
    const FIRST_GROUP_PATTERN = '(\$\d)';
    const NP_PATTERN = '\$NP'; // National Prefix
    const FG_PATTERN = '\$FG'; // First Group
    const CC_PATTERN = '\$CC'; // Carrier Code

    /**
     * The data loaded for external files.
     *
     * @var array
     */
    private $data = array();

    /**
     * The list of separator characters allowed during parsing.
     *
     * @var array
     */
    private $allowedSeparators = array(' ', '-', '.', '/', '(', ')');

    /**
     * Class constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $key
     * @return string
     */
    private function getDataFileName($key)
    {
        return __DIR__ . '/data/' . $key . '.php';
    }

    /**
     * @param string $key
     * @return bool
     */
    private function hasData($key)
    {
        if (isset($this->data[$key])) {
            return true;
        }

        return file_exists($this->getDataFileName($key));
    }

    /**
     * Loads data from an external file.
     *
     * The output of this method is cached to avoid redundant reads.
     *
     * @param string $key
     *
     * @return mixed
     */
    private function loadData($key)
    {
        if (! isset($this->data[$key])) {
            $this->data[$key] = require $this->getDataFileName($key);
        }

        return $this->data[$key];
    }

    /**
     * @param string $code
     * @return Territory
     * @throws \RuntimeException
     */
    private function getTerritory($code)
    {
        if (ctype_alnum($code)) {
            $key = 'territory-' . $code;
            if ($this->hasData($key)) {
                return $this->loadData($key);
            }
        }

        throw new \RuntimeException('Invalid territory: ' . $code);
    }

    /**
     * @return array
     */
    private function getCountryCodeToRegionCodesMap()
    {
        return $this->loadData('country-code-to-region-codes');
    }

    /**
     * @return array
     */
    private function getRegionCodeToCountryCodeMap()
    {
        return $this->loadData('region-code-to-country-code');
    }

    /**
     * @param string      $phoneNumber The phone number to parse.
     * @param string|null $regionCode  The country code to assume, if the number is not in international format.
     *
     * @return array (countryCode, nationalNumber)
     *
     * @throws PhoneNumberParseException
     */
    public function parse($phoneNumber, $regionCode = null)
    {
        $phoneNumber = str_replace($this->allowedSeparators, '', $phoneNumber);
        $isInternationalNumber = (substr($phoneNumber, 0, 1) == '+');

        if ($isInternationalNumber) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        if (! ctype_digit($phoneNumber)) {
            throw PhoneNumberParseException::illegalCharacters();
        }

        if ($isInternationalNumber) {
            $countryCode = $this->extractCountryCode($phoneNumber);
            $nationalNumber = substr($phoneNumber, strlen($countryCode));

            $this->checkLength($countryCode, $nationalNumber);

            return array($countryCode, $nationalNumber);
        }

        if ($regionCode === null) {
            throw PhoneNumberParseException::missingRegionCode();
        }

        $map = $this->getRegionCodeToCountryCodeMap();
        if (! isset($map[$regionCode])) {
            throw new PhoneNumberParseException('Unknown region code: ' . $regionCode);
        }

        $countryCode = $map[$regionCode];

        $territory = $this->getTerritory($regionCode);

        $nationalNumber = $phoneNumber;

        if ($territory->internationalPrefix !== null) {
            $internationalNumber = preg_replace('/^' . $territory->internationalPrefix . '/', '', $nationalNumber, -1, $count);
            if ($count == 1) {
                return self::parse('+' . $internationalNumber);
            }
        }

        if (! $territory->leadingZeroPossible) {
            $nationalNumber = preg_replace('/^0/', '', $nationalNumber);
        }

        $this->checkLength($countryCode, $nationalNumber);

        return array($countryCode, $nationalNumber);
    }

    /**
     * Extracts the country code out of an international phone number.
     *
     * @param string $phoneNumber The international phone number, without the leading + sign (digits only).
     * @return string
     * @throws PhoneNumberParseException
     */
    private function extractCountryCode($phoneNumber)
    {
        $map = $this->getCountryCodeToRegionCodesMap();

        for ($length = 1; $length <= 3; $length++) {
            $prefix = substr($phoneNumber, 0, $length);
            if (isset($map[$prefix])) {
                return $prefix;
            }
        }

        throw PhoneNumberParseException::doesNotMatchAnyCountryCode();
    }

    /**
     * @param string $countryCode
     * @param string $nationalNumber
     * @return void
     * @throws PhoneNumberParseException
     * @throws PhoneNumberParseException
     */
    private function checkLength($countryCode, $nationalNumber)
    {
        $length = strlen($countryCode) + strlen($nationalNumber);

        if ($length < self::MIN_LENGTH) {
            throw PhoneNumberParseException::tooShort();
        }

        if ($length > self::MAX_LENGTH) {
            throw PhoneNumberParseException::tooLong();
        }
    }

    /**
     * @param PhoneNumber $phoneNumber
     *
     * @return string|null
     */
    public function getRegionCodeForNumber(PhoneNumber $phoneNumber)
    {
        $map = $this->getCountryCodeToRegionCodesMap();
        $countryCode = $phoneNumber->getCountryCode();

        if (! isset($map[$countryCode])) {
            // This scenario would only theoretically be possible if a country code is removed from
            // the database, and we unserialize a PhoneNumber created with an earlier version.
            return null;
        }

        $regionCodes = $map[$countryCode];

        if (count($regionCodes) == 0) {
            // No region code: satellite phones, etc.
            return null;
        }

        if (count($regionCodes) == 1) {
            // Only one region, this is the one.
            return $regionCodes[0];
        }

        $nationalNumber = $phoneNumber->getNationalNumber();

        foreach ($regionCodes as $regionCode) {
            // If leadingDigits is present, use this. Otherwise, do full validation.
            $territory = $this->getTerritory($regionCode);
            if ($territory->leadingDigits !== null) {
                if (substr($nationalNumber, 0, strlen($territory->leadingDigits)) == $territory->leadingDigits) {
                    return $regionCode;
                }
            } else if ($this->getNumberTypeHelper($nationalNumber, $territory) != PhoneNumberType::UNKNOWN) {
                return $regionCode;
            }
        }

        return null;
    }

    /**
     * @param PhoneNumber $number
     * @return Territory|null
     */
    private function getTerritoryForNumber(PhoneNumber $number)
    {
        $regionCode = $this->getRegionCodeForNumber($number);

        if ($regionCode !== null) {
            return $this->getTerritory($regionCode);
        }

        $internationalNumber = substr($number, 1);
        try {
            $countryCode = $this->extractCountryCode($internationalNumber);
        }
        catch (PhoneNumberParseException $e) { // @todo dirty
            return null;
        }

        if (! $this->hasData('territory-' . $countryCode)) { // @todo dirty
            return null;
        }

        return $this->getTerritory($countryCode);
    }

    /**
     * @param PhoneNumber $phoneNumber
     * @return string
     */
    private function getNationalSignificantNumber(PhoneNumber $phoneNumber)
    {
        $territory = $this->getTerritoryForNumber($phoneNumber);
        $string = $territory->leadingZeroPossible ? '0' : '';

        return /* $string . */ $phoneNumber->getNationalNumber();
    }

    /**
     * @param string $nationalNumber
     * @param Territory $territory
     * @return string
     */
    private function getNumberTypeHelper($nationalNumber, Territory $territory)
    {
        if (! $this->isNumberMatchingDesc($nationalNumber, $territory->generalDesc)) {
            return PhoneNumberType::UNKNOWN;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->premiumRate)) {
            return PhoneNumberType::PREMIUM_RATE;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->tollFree)) {
            return PhoneNumberType::TOLL_FREE;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->sharedCost)) {
            return PhoneNumberType::SHARED_COST;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->voip)) {
            return PhoneNumberType::VOIP;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->personalNumber)) {
            return PhoneNumberType::PERSONAL_NUMBER;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->pager)) {
            return PhoneNumberType::PAGER;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->uan)) {
            return PhoneNumberType::UAN;
        }
        if ($this->isNumberMatchingDesc($nationalNumber, $territory->voicemail)) {
            return PhoneNumberType::VOICEMAIL;
        }

        $isFixedLine = $this->isNumberMatchingDesc($nationalNumber, $territory->fixedLine);
        $isMobile = $this->isNumberMatchingDesc($nationalNumber, $territory->mobile);

        if ($isFixedLine && $isMobile) {
            return PhoneNumberType::FIXED_LINE_OR_MOBILE;
        }
        if ($isFixedLine) {
            return PhoneNumberType::FIXED_LINE;
        }
        if ($isMobile) {
            return PhoneNumberType::MOBILE;
        }

        return PhoneNumberType::UNKNOWN;
    }

    /**
     * @param string $nationalNumber
     * @param NumberPattern|null $numberDesc
     * @return boolean
     */
    private function isNumberMatchingDesc($nationalNumber, NumberPattern $numberDesc = null)
    {
        if ($numberDesc === null) {
            return false;
        }
        if ($numberDesc->nationalNumberPattern === null) {
            return false;
        }

        if ($numberDesc->possibleNumberPattern !== null) {
            if (! $this->matches($nationalNumber, $numberDesc->possibleNumberPattern)) {
                return false;
            }
        }

        return $this->matches($nationalNumber, $numberDesc->nationalNumberPattern);
    }

    /**
     * Returns whether a string matches a pattern.
     *
     * @param string $string
     * @param string $pattern
     *
     * @return boolean
     */
    private function matches($string, $pattern)
    {
        return preg_match('/^' . $pattern . '$/', $string) == 1;
    }

    /**
     * @param PhoneNumber $phoneNumber
     * @return string
     */
    public function getNumberType(PhoneNumber $phoneNumber)
    {
        $territory = $this->getTerritoryForNumber($phoneNumber);

        if ($territory === null) {
            return PhoneNumberType::UNKNOWN;
        }

        return $this->getNumberTypeHelper($this->getNationalSignificantNumber($phoneNumber), $territory);
    }

    /**
     * @param PhoneNumber $phoneNumber
     *
     * @return boolean
     */
    public function isValidNumber(PhoneNumber $phoneNumber)
    {
        return $this->getNumberType($phoneNumber) != PhoneNumberType::UNKNOWN;
    }

    /**
     * @param PhoneNumber $number
     * @param string      $format
     *
     * @return string
     */
    public function format(PhoneNumber $number, $format)
    {
        // @todo
    }
}
