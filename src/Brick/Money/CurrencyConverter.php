<?php

namespace Brick\Money;

use Brick\Locale\Currency;

interface CurrencyConverter
{
    /**
     * @param Money    $money
     * @param Currency $currency
     *
     * @return Money
     */
    public function convert(Money $money, Currency $currency);
}
