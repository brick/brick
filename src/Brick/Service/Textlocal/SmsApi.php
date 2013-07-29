<?php

namespace Brick\Service\TextLocal;

use Brick\Json\Json;
use Brick\Service\Exception;
use Brick\Curl\Curl;

/**
 * Class implementing the TxtLocal SMS API.
 */
class SmsApi
{
    const API_URL = 'https://www.txtlocal.com/sendsmspost.php';

    protected $username;
    protected $password;

    /**
     * Class constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = (string) $username;
        $this->password = (string) $password;
    }

    /**
     * Sends a SMS message to one or several recipients.
     *
     * @param array  $numbers The numbers in international format, without the + sign
     * @param string $from    The sender name
     * @param string $message The message to send
     *
     * @throws \Brick\Service\Exception
     */
    public function sendSms(array $numbers, $from, $message)
    {
        foreach ($numbers as $number) {
            if (! ctype_digit($number)) {
                throw new Exception('Invalid phone number: ' . $number);
            }
        }

        $numbers = implode(',', $numbers);

        $postData = array(
            'uname'   => $this->username,
            'pword'   => $this->password,
            'numbers' => $numbers,
            'message' => $message,
            'from'    => $from,
            'json'    => '1'
        );

        $curl = new Curl(self::API_URL);

        $curl->setOption(CURLOPT_POST, true);
        $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));

        $json = $curl->execute();
        $data = Json::decode($json);

        if (isset($data->Error)) {
            throw new Exception('TextLocal error: ' . $data->Error);
        }
    }
}
