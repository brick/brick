<?php

namespace Brick\Validation\Validator;

use Brick\Validation\AbstractValidator;

/**
 * Validates the length of a string.
 *
 * @deprecated Use StringValidator
 */
class LengthValidator extends AbstractValidator
{
    /**
     * @var integer|null
     */
    private $minLength = null;

    /**
     * @var integer|null
     */
    private $maxLength = null;

    /**
     * {@inheritdoc}
     */
    public function getPossibleMessages()
    {
        return [
            'validator.length.too-short' => 'The string is too short.',
            'validator.length.too-long'  => 'The string is too long.'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function validate($value)
    {
        $length = strlen($value);

        if ($this->minLength !== null && $length < $this->minLength) {
            $this->addFailureMessage('validator.length.too-short');
        } elseif ($this->maxLength !== null && $length > $this->maxLength) {
            $this->addFailureMessage('validator.length.too-long');
        }
    }

    /**
     * Sets a minimum length.
     *
     * @param integer $minLength
     *
     * @return static
     */
    public function setMinLength($minLength)
    {
        $this->minLength = (int) $minLength;

        return $this;
    }

    /**
     * Sets a maximum length.
     *
     * @param integer $maxLength
     *
     * @return static
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = (int) $maxLength;

        return $this;
    }

    /**
     * Sets an exact length.
     *
     * @param integer $length
     *
     * @return static
     */
    public function setLength($length)
    {
        $length = (int) $length;

        $this->minLength = $length;
        $this->maxLength = $length;

        return $this;
    }
}
