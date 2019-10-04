<?php

namespace App\Helpers;

use App\Helpers\Config;

class Flip
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function disbursement(array $data)
    {
        return $this->post('/disburse', $data);
    }

    private function post($endpoint, $data)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->config->flip->url . $endpoint,
            CURLOPT_USERPWD        => $this->config->flip->secret_key . ':',
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $response = curl_exec($ch);

        if($errno = curl_errno($ch)) {
            $errMessage = curl_strerror($errno);
            die("cURL error ($errno): $errMessage");
        }

        curl_close($ch);

        return $response;
    }
}
