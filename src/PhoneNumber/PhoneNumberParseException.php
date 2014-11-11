<?php

namespace Brick\PhoneNumber;

/**
 * Exception thrown when an error occurs during the parsing of a phone number.
 */
class PhoneNumberParseException extends \RuntimeException
{
    /**
     * @return PhoneNumberParseException
     */
    public static function illegalCharacters()
    {
        return new self('The phone number contains illegal characters');
    }

    /**
     * @return PhoneNumberParseException
     */
    public static function tooShort()
    {
        return new self('The phone number is too short');
    }

    /**
     * @return PhoneNumberParseException
     */
    public static function tooLong()
    {
        return new self('The phone number is too long');
    }

    /**
     * @return PhoneNumberParseException
     */
    public static function doesNotMatchAnyCountryCode()
    {
        return new self('The phone number does not match any country code');
    }

    /**
     * @return PhoneNumberParseException
     */
    public static function missingRegionCode()
    {
        return new self('A region code must be given when the phone number is not in international format');
    }
}
