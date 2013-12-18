<?php

namespace Brick\Money;

use Brick\Locale\Currency;
use Brick\Math\Decimal;
use Brick\Locale\Locale;

/**
 * Represents a monetary value in a given currency. This class is immutable.
 */
class Money
{
    /**
     * @var \Brick\Locale\Currency
     */
    private $currency;

    /**
     * @var \Brick\Math\Decimal
     */
    private $amount;

    /**
     * Class constructor.
     *
     * @param Currency $currency
     * @param Decimal  $amount
     * @param boolean  $isExact
     */
    public function __construct(Currency $currency, Decimal $amount, $isExact = true)
    {
        $this->currency = $currency;
        $this->amount   = $amount->withScale($currency->getDefaultFractionDigits(), $isExact);
    }

    /**
     * @param Currency $currency
     * @param string   $amount
     *
     * @return Money
     */
    public static function of(Currency $currency, $amount)
    {
        $amount = Decimal::of($amount);

        return new self($currency, $amount);
    }

    /**
     * Parses a string representation of a money, such as USD 23.00.
     *
     * @param string $string
     *
     * @return Money
     *
     * @throws \InvalidArgumentException
     */
    public static function parse($string)
    {
        $parts = explode(' ', $string);

        if (count($parts) != 2) {
            throw new \InvalidArgumentException('Could not parse money: ' . $string);
        }

        return Money::of(Currency::getInstance($parts[0]), $parts[1]);
    }

    /**
     * Returns a Money with zero value, in the given Currency.
     *
     * @param Currency $currency
     *
     * @return Money
     */
    public static function zero(Currency $currency)
    {
        return new self($currency, Decimal::zero());
    }

    /**
     * Returns the Currency of this Money.
     *
     * @return \Brick\Locale\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Returns the amount of this Money, as a Decimal.
     *
     * @return \Brick\Math\Decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Money $that
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    private function checkCurrencyMatches(Money $that)
    {
        if (! $that->currency->isEqualTo($this->currency)) {
            throw new \RuntimeException(sprintf('Currencies mismatch: %s != %s',
                    $that->currency->getCurrencyCode(),
                    $this->currency->getCurrencyCode())
            );
        }
    }

    /**
     * @param Money $that
     *
     * @return Money
     */
    public function plus(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return new Money($this->currency, $this->amount->plus($that->getAmount()));
    }

    /**
     * @param Money $that
     *
     * @return Money
     */
    public function minus(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return new Money($this->currency, $this->amount->minus($that->getAmount()));
    }

    /**
     * @param Decimal $that
     * @param boolean $isExact
     *
     * @return Money
     */
    public function multipliedBy(Decimal $that, $isExact = true)
    {
        return new Money($this->currency, $this->amount->multipliedBy($that), $isExact);
    }

    /**
     * @param Decimal $that
     * @param boolean $isExact
     *
     * @return Money
     */
    public function dividedBy(Decimal $that, $isExact = true)
    {
        $amount = $this->amount->dividedBy($that, $this->currency->getDefaultFractionDigits(), $isExact);

        return new Money($this->currency, $amount);
    }

    /**
     * Returns a copy of this Money with the value negated.
     *
     * @return Money
     */
    public function negated()
    {
        return new Money($this->currency, $this->amount->negated());
    }

    /**
     * Returns whether this Money has zero value.
     *
     * @return boolean
     */
    public function isZero()
    {
        return $this->amount->isZero();
    }

    /**
     * Returns whether this Money has a negative value.
     *
     * @return boolean
     */
    public function isNegative()
    {
        return $this->amount->isNegative();
    }

    /**
     * Returns whether this Money has a negative or zero value.
     *
     * @return boolean
     */
    public function isNegativeOrZero()
    {
        return $this->amount->isNegativeOrZero();
    }

    /**
     * Returns whether this Money has a positive value.
     *
     * @return boolean
     */
    public function isPositive()
    {
        return $this->amount->isPositive();
    }

    /**
     * Returns whether this Money has a positive or zero value.
     *
     * @return boolean
     */
    public function isPositiveOrZero()
    {
        return $this->amount->isPositiveOrZero();
    }

    /**
     * Returns whether this Money is equal to the given Money.
     *
     * @param  Money   $that
     * @return boolean
     */
    public function isEqualTo(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return $this->amount->isEqualTo($that->amount);
    }

    /**
     * Returns whether this Money is less than the given Money.
     *
     * @param Money $that
     *
     * @return boolean
     */
    public function isLessThan(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return $this->amount->isLessThan($that->amount);
    }

    /**
     * Returns whether this Money is less than or equal to the given Money.
     *
     * @param Money $that
     *
     * @return boolean
     */
    public function isLessThanOrEqualTo(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return $this->amount->isLessThanOrEqualTo($that->amount);
    }

    /**
     * Returns whether this Money is greater than the given Money.
     *
     * @param Money $that
     *
     * @return boolean
     */
    public function isGreaterThan(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return $this->amount->isGreaterThan($that->amount);
    }

    /**
     * Returns whether this Money is greater than or equal to the given Money.
     *
     * @param Money $that
     *
     * @return boolean
     */
    public function isGreaterThanOrEqualTo(Money $that)
    {
        $this->checkCurrencyMatches($that);

        return $this->amount->isGreaterThanOrEqualTo($that->amount);
    }

    /**
     * Returns a formatted string, according to the given Locale.
     *
     * Note: even though all calculations are done with exact arithmetic,
     * the NumberFormatter here accepts a floating-point value only.
     * This should not be an issue for formatting though, as the output has been
     * verified to be the correct one on the range [-1000000.00,1000000.00]
     *
     * @param Locale $locale
     *
     * @return string
     */
    public function format(Locale $locale)
    {
        $formatter = new \NumberFormatter($locale->toString(), \NumberFormatter::CURRENCY);
        $formatter->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $this->currency->getSymbol());

        return $formatter->format((float) $this->amount->toString());
    }

    /**
     * @return BigMoney
     */
    public function toBigMoney()
    {
        // @todo
    }

    /**
     * Returns a non-localized string representation of this Money
     * e.g. "EUR 25.00"
     *
     * @return string
     */
    public function toString()
    {
        return $this->currency->getCurrencyCode() . ' ' . $this->amount->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
