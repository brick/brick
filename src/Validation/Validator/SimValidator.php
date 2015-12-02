<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;
use Brick\Checksum\Luhn;

/**
 * Validates a SIM card number (ICCID).
 *
 * Note about check digits: all SIM card numbers should have a check digit, but in practice, many SIM card issuers
 * provide SIM card numbers without a check digit.
 *
 * The SIM card numbers can have 18 or 19 digits + 1 check digit.
 *
 * This validator always checks that the number contains only digits, and is of a valid length.
 *
 * The default behaviour, if no argument is passed to the constructor, is to accept any form of SIM card number,
 * with and without a check digit. In this configuration, only numbers with 20 digits will be verified for a valid
 * check digit, as 18 digits numbers do not have one, and 19 digits numbers might be 18 digits numbers with a check
 * digit, or 19 digits numbers without one, so in doubt, we cannot perform the verification.
 *
 * If `true` is passed to the constructor, then the valid lengths will be 19 and 20 digits, and the check digit
 * will always be validated.
 *
 * If `false` is passed to the constructor, then the valid lengths will be 18 and 19 digits, and the check digit
 * will never be validated.
 */
class SimValidator extends AbstractValidator
{
    /**
     * Whether the number to validate has a check digit, or null if unknown.
     *
     * @var bool|null
     */
    private $hasCheckDigit;

    /**
     * SimValidator constructor.
     *
     * @param bool|null $hasCheckDigit Whether the number to validate has a check digit, or null if unknown.
     */
    public function __construct($hasCheckDigit = null)
    {
        $this->hasCheckDigit = $hasCheckDigit;
    }

    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.sim.invalid' => 'Invalid SIM card number.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        $length = strlen($value);

        $minLength = ($this->hasCheckDigit === true) ? 19 : 18;
        $maxLength = ($this->hasCheckDigit === false) ? 19 : 20;

        if ($length < $minLength || $length > $maxLength) {
            $this->addFailureMessage('validator.sim.invalid');

            return;
        }

        if ($this->hasCheckDigit === true || $length == 20) {
            if (Luhn::isValid($value)) {
                return;
            }
        } else {
            if (ctype_digit($value)) {
                return;
            }
        }

        $this->addFailureMessage('validator.sim.invalid');
    }
}
