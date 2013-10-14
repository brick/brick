<?php

namespace Brick\Service\Maxmind;

use Brick\Service\Exception;
use Brick\Curl\Curl;

/**
 * Class implementing the MaxMind GeoIP API.
 */
class GeoIp
{
    const COUNTRY   = 'country';
    const CITY      = 'city';
    const CITY_ISP  = 'city_isp';

    /**
     * The MaxMind license key.
     *
     * @var string
     */
    protected $licenseKey;

    /**
     * The MaxMind GeoIP service.
     * One of the constants COUNTRY|CITY|CITY_ISP.
     *
     * @var string
     */
    protected $service;

    /**
     * @var array
     */
    protected static $uris = [
        self::COUNTRY  => 'http://geoip.maxmind.com/a',
        self::CITY     => 'http://geoip.maxmind.com/b',
        self::CITY_ISP => 'http://geoip.maxmind.com/f'
    ];

    /**
     * @var array
     */
    protected static $fields = [
        self::COUNTRY => [
            'country'
        ],
        self::CITY  => [
            'country',
            'region',
            'city',
            'latitude',
            'longitude'
        ],
        self::CITY_ISP => [
            'country',
            'region',
            'city',
            'postalCode',
            'latitude',
            'longitude',
            'metropolitanCode',
            'areaCode',
            'isp',
            'organization'
        ]
    ];

    /**
     * Class constructor.
     *
     * @param string $licenseKey The MaxMind license key.
     * @param string $service    The MaxMind service to use.
     */
    public function __construct($licenseKey, $service)
    {
        $this->setLicenseKey($licenseKey);
        $this->setService($service);
    }

    /**
     * Sets the MaxMind license key.
     *
     * @param  string $key
     * @return GeoIp
     */
    public function setLicenseKey($key)
    {
        $this->licenseKey = (string) $key;

        return $this;
    }

    /**
     * Returns the MaxMind license key.
     *
     * @return string
     */
    public function getLicenseKey()
    {
        return $this->licenseKey;
    }

    /**
     * Sets the MaxMind GeoIP service.
     *
     * @param  string                    $service
     * @return GeoIp
     * @throws \Brick\Service\Exception
     */
    public function setService($service)
    {
        if (! isset(self::$uris[$service])) {
            throw new Exception('Unknown service: ' . $service);
        }

        $this->service = $service;

        return $this;
    }

    /**
     * Returns the MaxMind GeoIP service.
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        return self::$uris[$this->service];
    }

    /**
     * @return array
     */
    protected function getFields()
    {
        return self::$fields[$this->service];
    }

    /**
     * Queries an IP address.
     *
     * @param  string $ip
     * @return array
     * @throws Exception
     */
    public function queryIp($ip)
    {
        $parameters = [
            'l' => $this->licenseKey,
            'i' => $ip
        ];

        $curl = new Curl($this->getUri(), $parameters);
        $result = $curl->execute();

        $parts = explode(',', $result);

        $fields = $this->getFields();

        if (count($parts) == count($fields) + 1) {
            $error = $parts[count($fields)];

//			if ($error == 'IP_NOT_FOUND') {
//				return false;
//			}

            throw new Exception('Maxmind error message: ' . $error);
        }

        if (count($parts) != count($fields)) {
            throw new Exception('Error parsing response. Message received: ' . $data);
        }

        $return = [];
        foreach ($fields as $key => $field) {
            $return[$field] = $parts[$key];
        }

        return $return;
    }
}
