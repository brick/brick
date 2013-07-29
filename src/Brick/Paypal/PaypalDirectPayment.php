<?php

namespace Brick\Paypal;

/**
 * This class handles communication with the Paypal Direct Payement API.
 */
class PaypalDirectPayment extends AbstractPaypal
{
    /**
     * Calls the DoDirectPayment method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function doDirectPayment(array $params)
    {
        return $this->call('DoDirectPayment', $params);
    }
}