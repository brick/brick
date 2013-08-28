<?php

namespace Brick\Money;

/**
 * An amount of money with unrestricted decimal place precision.
 *
 * Every currency has a certain standard number of decimal places.
 * This is typically 2 (Euro, British Pound, US Dollar) but might be
 * 0 (Japanese Yen), 1 (Vietnamese Dong) or 3 (Bahrain Dinar).
 * The BigMoney class is not restricted to the standard decimal places
 * and can represent an amount to any precision that a BigDecimal can represent.
 *
 * This class is immutable.
 *
 * @todo
 */
class BigMoney
{
    /**
     * @return Money
     */
    public function toMoney()
    {
    }
}
