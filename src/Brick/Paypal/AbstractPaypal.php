<?php
namespace Brick\Paypal;

abstract class AbstractPaypal {
    /**
     * The API username provided by Paypal.
     *
     * @var string
     */
    protected $user;

    /**
     * The API password provided by Paypal.
     *
     * @var string
     */
    protected $password;

    /**
     * The API signature provided by Paypal.
     *
     * @var string
     */
    protected $signature;

    /**
     * The Paypal NVP API endpoint (differs for live or sandbox).
     *
     * @var string
     */
    protected $endpoint;

    /**
     * The Paypal NVP API version to use (XX.X).
     *
     * @var string
     */
    protected $version;

    /**
     * Class constructor.
     *
     * @param string $user
     * @param string $password
     * @param string $signature
     * @param string $endpoint
     * @param string $version
     */
    public function __construct($user, $password, $signature, $endpoint, $version)
    {
        $this->user      = (string) $user;
        $this->password  = (string) $password;
        $this->signature = (string) $signature;
        $this->endpoint  = (string) $endpoint;
        $this->version   = (string) $version;
    }

    /**
     * Calls the Paypal API, and returns the response as an associative array (name-value pairs).
     *
     * @param  string          $method
     * @param  array           $params
     * @return array
     * @throws PaypalException
     */
    protected function call($method, array $params)
    {
        $params['METHOD']    = $method;
        $params['VERSION']   = $this->version;
        $params['USER']      = $this->user;
        $params['PWD']       = $this->password;
        $params['SIGNATURE'] = $this->signature;

        $params = http_build_query($params);

        $ch = curl_init($this->endpoint);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($errno != CURLE_OK) {
            throw new PaypalException('Could not communicate with Paypal: ' . $error);
        }

        parse_str($response, $array);

        return $array;
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