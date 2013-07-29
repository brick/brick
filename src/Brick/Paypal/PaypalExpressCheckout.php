<?php

namespace Brick\Paypal;

/**
 * This class handles communication with the Paypal Express Checkout API.
 */
class PaypalExpressCheckout extends AbstractPaypal
{
    /**
     * Calls the SetExpressCheckout method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function setExpressCheckout(array $params)
    {
        return $this->call('SetExpressCheckout', $params);
    }

    /**
     * Calls the GetExpressCheckoutDetails method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function getExpressCheckoutDetails(array $params)
    {
        return $this->call('GetExpressCheckoutDetails', $params);
    }

    /**
     * Calls the DoExpressCheckoutPayment method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function doExpressCheckoutPayment(array $params)
    {
        return $this->call('DoExpressCheckoutPayment', $params);
    }

    /**
     * Calls the doCapture method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function doCapture(array $params)
    {
        return $this->call('DoCapture', $params);
    }

    /**
     * Calls the DoReauthorization method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function doReauthorization(array $params)
    {
        return $this->call('DoReauthorization', $params);
    }

    /**
     * Calls the DoVoid method, and returns the reponse as an associative array.
     *
     * @param  array $params
     * @return array
     */
    public function doVoid(array $params)
    {
        return $this->call('DoVoid', $params);
    }
}
