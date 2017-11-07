<?php

declare(strict_types=1);

namespace Brick\Paypal;

/**
 * Communicates with the Paypal NVP API.
 */
class Paypal
{
    /**
     * The API username provided by Paypal.
     *
     * @var string
     */
    private $user;

    /**
     * The API password provided by Paypal.
     *
     * @var string
     */
    private $password;

    /**
     * The API signature provided by Paypal.
     *
     * @var string
     */
    private $signature;

    /**
     * The Paypal NVP API endpoint (differs for live or sandbox).
     *
     * @var string
     */
    private $endpoint;

    /**
     * The Paypal NVP API version to use (XX.X).
     *
     * @var string
     */
    private $version;

    /**
     * Class constructor.
     *
     * @param string $user
     * @param string $password
     * @param string $signature
     * @param string $endpoint
     * @param string $version
     */
    public function __construct(string $user, string $password, string $signature, string $endpoint, string $version)
    {
        $this->user      = $user;
        $this->password  = $password;
        $this->signature = $signature;
        $this->endpoint  = $endpoint;
        $this->version   = $version;
    }

    /**
     * Calls the Paypal API, and returns the response as an associative array (name-value pairs).
     *
     * @param string $method
     * @param array  $params
     *
     * @return array
     *
     * @throws PaypalException
     */
    public function call(string $method, array $params) : array
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

        if ($errno !== CURLE_OK) {
            throw new PaypalException('Could not communicate with Paypal: ' . $error);
        }

        parse_str($response, $array);

        return $array;
    }
}
