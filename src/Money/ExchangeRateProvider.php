<?php

namespace Brick\Money;

use Brick\Locale\Currency;

/**
 * Interface for exchange rate providers.
 */
interface ExchangeRateProvider
{
    /**
     * @param Currency $source The source currency.
     * @param Currency $target The target currency.
     *
     * @return float The exchange rate.
     */
    public function getExchangeRate(Currency $source, Currency $target);
}
