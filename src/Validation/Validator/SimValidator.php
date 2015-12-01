<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;
use Brick\Checksum\Luhn;

/**
 * Validates a SIM card number (ICCID).
 *
 * Note about check digits: all SIM card numbers should have a check digit, but in practice, many SIM card issuers
 * provide SIM card numbers without a check digit. It is possible to calculate the missing check digit, but this
 * goes outside the scope of this validator, and might bring confusion anyway if some SIM card numbers are used
 * with a check digit, and others without.
 *
 * This validator always checks that the number contains only digits, and is of the correct length.
 * By default, it also checks that the number contains the correct check digit, unless disabled in the constructor.
 */
class SimValidator extends AbstractValidator
{
    /**
     * Whether the number to validate has a check digit.
     *
     * @var bool
     */
    private $hasCheckDigit;

    /**
     * SimValidator constructor.
     *
     * @param bool $hasCheckDigit Whether the number to validate has a check digit.
     */
    public function __construct($hasCheckDigit = true)
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

        $minLength = 18;
        $maxLength = 19;

        if ($this->hasCheckDigit) {
            $minLength++;
            $maxLength++;
        }

        if ($length < $minLength || $length > $maxLength) {
            $this->addFailureMessage('validator.sim.invalid');

            return;
        }

        if ($this->hasCheckDigit) {
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
