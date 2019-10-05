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

    /**
     * Handler to disbursement
     *
     * @param array $data
     * @return json JSON response
     */
    public function disbursement(array $data)
    {
        return $this->post('/disburse', $data);
    }

    /**
     * Handler to check status by given transaction id
     *
     * @param string $transactionId We use string to handle hash transaction id
     * @return json JSON response
     */
    public function checkStatus(string $transactionId)
    {
        return $this->get('/disburse/'.$transactionId);
    }

    /**
     * Handler to curl post
     *
     * @param string $endpoint
     * @param array $data
     * @return json JSON response
     */
    private function post(string $endpoint, array $data = [])
    {
        return $this->send($endpoint, [
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $data
        ]);
    }

    /**
     * Handler to curl get
     *
     * @param string $endpoint
     * @param array $data 
     * @return json JSON response
     */
    private function get(string $endpoint, array $data = [])
    {
        return $this->send($endpoint, [
            CURLOPT_POST => false
        ]);
    }

    /**
     * Wrapper to perform curl action
     *
     * @param string $endpoint
     * @param array $options
     * @return json $response
     */
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
