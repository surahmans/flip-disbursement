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

    public function checkStatus(string $transactionId)
    {
        return $this->get('/disburse/'.$transactionId);
    }

    private function post(string $endpoint, array $data = [])
    {
        return $this->send($endpoint, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $data
        ]);
    }

    private function get(string $endpoint, array $data = [])
    {
        return $this->send($endpoint, [
            CURLOPT_POST => false
        ]);
    }

    private function send(string $endpoint, array $options)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->config->flip->url . $endpoint,
            CURLOPT_USERPWD        => $this->config->flip->secret_key . ':',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_TIMEOUT        => 15,
        ] + $options);
        $response = curl_exec($ch);

        if($errno = curl_errno($ch)) {
            $errMessage = curl_strerror($errno);
            die("cURL error ($errno): $errMessage");
        }

        curl_close($ch);

        return $response;
    }
}
