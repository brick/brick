<?php

namespace Brick\Money;

use Brick\Locale\Currency;

/**
 * Contains monies in different currencies. This class is mutable.
 */
class MoneyBag
{
    /**
     * The monies in this bag, indexed by currency code.
     *
     * @var Money[]
     */
    private $monies;

    /**
     * Returns the money in the given currency contained in the bag.
     *
     * If no money is present for the given currency, a zero-value money will be returned.
     *
     * @param Currency $currency
     *
     * @return Money
     */
    public function get(Currency $currency)
    {
        $currencyCode = $currency->getCurrencyCode();

        return isset($this->monies[$currencyCode]) ? $this->monies[$currencyCode] : Money::zero($currency);
    }

    /**
     * Adds the given money to this bag.
     *
     * @param Money $money
     *
     * @return MoneyBag
     */
    public function add(Money $money)
    {
        $currency = $money->getCurrency();
        $currencyCode = $currency->getCurrencyCode();
        $this->monies[$currencyCode] = $money->plus($this->get($currency));

        return $this;
    }

    /**
     * Subtracts the given money from this bag.
     *
     * @param Money $money
     *
     * @return MoneyBag
     */
    public function subtract(Money $money)
    {
        return $this->add($money->negated());
    }
}
