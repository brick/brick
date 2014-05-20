<?php

namespace Brick\Money;

use Brick\Locale\Currency;
use Brick\Locale\Locale;
use Brick\Math\Decimal;
use Brick\Math\RoundingMode;
use Brick\Math\ArithmeticException;

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
     * @param Currency $currency The currency.
     * @param Decimal  $amount   The amount, with scale matching the currency's fraction digits.
     */
    private function __construct(Currency $currency, Decimal $amount)
    {
        $this->currency = $currency;
        $this->amount   = $amount;
    }

    /**
     * Returns the minimum of the given values.
     *
     * @param Money $a
     * @param Money $b
     *
     * @return \Brick\Money\Money
     */
    public static function min(Money $a, Money $b)
    {
        return $a->isLessThan($b) ? $a : $b;
    }

    /**
     * Returns the maximum of the given values.
     *
     * @param Money $a
     * @param Money $b
     *
     * @return \Brick\Money\Money
     */
    public static function max(Money $a, Money $b)
    {
        return $a->isGreaterThan($b) ? $a : $b;
    }

    /**
     * @param Currency|string             $currency     A Currency instance or currency code.
     * @param Money|Decimal|number|string $amount       A Money instance or decimal amount.
     * @param integer                     $roundingMode The rounding mode to use.
     *
     * @return Money
     *
     * @throws CurrencyMismatchException If a money used as amount does not match the given currency.
     * @throws ArithmeticException       If the scale exceeds the currency scale and no rounding is requested.
     * @throws \InvalidArgumentException If an invalid rounding mode is given.
     */
    public static function of($currency, $amount, $roundingMode = RoundingMode::UNNECESSARY)
    {
        $currency = Currency::of($currency);

        if ($amount instanceof Money) {
            if ($amount->getCurrency()->isEqualTo($currency)) {
                return $amount;
            }

            throw CurrencyMismatchException::currencyMismatch($currency, $amount->getCurrency());
        }

        $scale  = $currency->getDefaultFractionDigits();
        $amount = Decimal::of($amount)->withScale($scale, $roundingMode);

        return new Money($currency, $amount);
    }

    /**
     * Parses a string representation of a money, such as USD 23.00.
     *
     * @param Money|string $string
     *
     * @return \Brick\Money\Money
     *
     * @throws \Brick\Money\MoneyParseException If the parsing fails.
     */
    public static function parse($string)
    {
        if ($string instanceof Money) {
            return $string;
        }

        $parts = explode(' ', $string);

        if (count($parts) != 2) {
            throw MoneyParseException::invalidFormat($string);
        }

        try {
            $currency = Currency::of($parts[0]);
            $amount   = Decimal::of($parts[1]);
        }
        catch (\InvalidArgumentException $e) {
            throw MoneyParseException::wrap($e);
        }

        try {
            return Money::of($currency, $amount);
        }
        catch (ArithmeticException $e) {
            throw MoneyParseException::wrap($e);
        }
    }

    /**
     * Returns a Money with zero value, in the given Currency.
     *
     * @param \Brick\Locale\Currency $currency
     *
     * @return \Brick\Money\Money
     */
    public static function zero(Currency $currency)
    {
        return Money::of($currency, 0);
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
     * @param Money|Decimal|number|string $that
     *
     * @return \Brick\Money\Money
     */
    public function plus($that)
    {
        $that = Money::of($this->currency, $that);

        return new Money($this->currency, $this->amount->plus($that->amount));
    }

    /**
     * @param Money|Decimal|number|string $that
     *
     * @return \Brick\Money\Money
     */
    public function minus($that)
    {
        $that = Money::of($this->currency, $that);

        return new Money($this->currency, $this->amount->minus($that->amount));
    }

    /**
     * @param Decimal|number|string $that
     * @param integer               $roundingMode
     *
     * @return \Brick\Money\Money
     */
    public function multipliedBy($that, $roundingMode = RoundingMode::UNNECESSARY)
    {
        $that = Decimal::of($that);

        $scale  = $this->currency->getDefaultFractionDigits();
        $amount = $this->amount->multipliedBy($that)->withScale($scale, $roundingMode);

        return new Money($this->currency, $amount);
    }

    /**
     * @param Decimal|number|string $that
     * @param integer               $roundingMode
     *
     * @return \Brick\Money\Money
     */
    public function dividedBy($that, $roundingMode = RoundingMode::UNNECESSARY)
    {
        $that = Decimal::of($that);

        $scale  = $this->currency->getDefaultFractionDigits();
        $amount = $this->amount->dividedBy($that, $scale, $roundingMode);

        return new Money($this->currency, $amount);
    }

    /**
     * Returns a copy of this Money with the value negated.
     *
     * @return \Brick\Money\Money
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
     * @param Money|Decimal|number|string $that
     *
     * @return boolean
     *
     * @throws CurrencyMismatchException
     */
    public function isEqualTo($that)
    {
        $that = Money::of($this->currency, $that);

        return $this->amount->isEqualTo($that->amount);
    }

    /**
     * Returns whether this Money is less than the given amount.
     *
     * @param Money|Decimal|number|string $that
     *
     * @return boolean
     *
     * @throws CurrencyMismatchException
     */
    public function isLessThan($that)
    {
        $that = Money::of($this->currency, $that);

        return $this->amount->isLessThan($that->amount);
    }

    /**
     * Returns whether this Money is less than or equal to the given amount.
     *
     * @param Money|Decimal|number|string $that
     *
     * @return boolean
     *
     * @throws CurrencyMismatchException
     */
    public function isLessThanOrEqualTo($that)
    {
        $that = Money::of($this->currency, $that);

        return $this->amount->isLessThanOrEqualTo($that->amount);
    }

    /**
     * Returns whether this Money is greater than the given Money.
     *
     * @param Money|Decimal|number|string $that
     *
     * @return boolean
     *
     * @throws CurrencyMismatchException
     */
    public function isGreaterThan($that)
    {
        $that = Money::of($this->currency, $that);

        return $this->amount->isGreaterThan($that->amount);
    }

    /**
     * Returns whether this Money is greater than or equal to the given Money.
     *
     * @param Money|Decimal|number|string $that
     *
     * @return boolean
     *
     * @throws CurrencyMismatchException
     */
    public function isGreaterThanOrEqualTo($that)
    {
        $that = Money::of($this->currency, $that);

        return $this->amount->isGreaterThanOrEqualTo($that->amount);
    }

    /**
     * Returns a string containing the major value of the money.
     *
     * Example: 123.45 will return '123'.
     *
     * @return string
     */
    public function getAmountMajor()
    {
        return $this->amount->withScale(0, RoundingMode::DOWN)->getUnscaledValue();
    }

    /**
     * Returns a string containing the minor value of the money.
     *
     * Example: 123.45 will return '45'.
     *
     * @return string
     */
    public function getAmountMinor()
    {
        return substr($this->amount->getUnscaledValue(), - $this->currency->getDefaultFractionDigits());
    }

    /**
     * Returns a string containing the value of this money in cents.
     *
     * Example: 123.45 USD will return '12345'.
     *
     * @return string
     */
    public function getAmountCents()
    {
        return $this->amount->getUnscaledValue();
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
        return $this->currency->getCode() . ' ' . $this->amount->toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
