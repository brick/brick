<?php

namespace Brick\Money;

use Brick\Locale\Currency;
use Brick\Math\RoundingMode;

/**
 * Converts monies into different currencies, using an exchange rate provider.
 */
class CurrencyConverter
{
    /**
     * @var ExchangeRateProvider
     */
    private $exchangeRateProvider;

    /**
     * @var integer
     */
    private $roundingMode = RoundingMode::FLOOR;

    /**
     * @param ExchangeRateProvider $exchangeRateProvider The exchange rate provider.
     * @param integer              $roundingMode         The rounding mode to use for conversions.
     */
    public function __construct(ExchangeRateProvider $exchangeRateProvider, $roundingMode = RoundingMode::FLOOR)
    {
        $this->exchangeRateProvider = $exchangeRateProvider;
        $this->roundingMode         = $roundingMode;
    }

    /**
     * @param Money    $money
     * @param Currency $currency
     *
     * @return Money
     */
    public function convert(Money $money, Currency $currency)
    {
        if ($money->getCurrency()->getCode() == $currency->getCode()) {
            return $money;
        }

        $exchangeRate = $this->exchangeRateProvider->getExchangeRate($money->getCurrency(), $currency);

        $amount = $money->getAmount()->multipliedBy($exchangeRate);

        return Money::of($currency, $amount, $this->roundingMode);
    }
}
