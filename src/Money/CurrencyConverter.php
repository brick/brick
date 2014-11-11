<?php

namespace Brick\Money;

use Brick\Locale\Currency;

/**
 * Interface for currency converters.
 */
interface CurrencyConverter
{
    /**
     * @param \Brick\Locale\Currency $from
     * @param \Brick\Locale\Currency $to
     *
     * @return \Brick\Math\BigDecimal
     */
    public function getExchangeRate(Currency $from, Currency $to);

    /**
     * @param \Brick\Money\Money     $money
     * @param \Brick\Locale\Currency $currency
     *
     * @return \Brick\Money\Money
     */
    public function convert(Money $money, Currency $currency);
}
