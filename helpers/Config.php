<?php

namespace App\Helpers;

use Exception;

class Config
{
    protected $config;

    public function __construct()
    {
        $this->config = $this->getConfig();

        return $this;
    }

    /**
     * Get config like accessing a property
     *
     * @param string $name
     *
     * @return mixed 
     */
    public function __get($name)
    {
        /**
         * Get the property instead of config key if the property exist
         */
        if (property_exists(Config::class, $name)) {
            return $this->{$name};
        }

        if (! array_key_exists($name, $this->config)) {
            throw new Exception(sprintf('Key %s not exist', $name));
        }

        $value = $this->config[$name];

        /**
         * If the key is an array, return object of config and set config with that array as value.
         * So, we can chain access. For example: $config->database->host
         */
        if (is_array($value)) {
            $this->config = $value;

            return $this;
        }

        return $value;
    }

    private function getConfig()
    {
        return json_decode($this->getConfigFile(), true);
    }

    private function getConfigFile()
    {
        $filename = 'config.json';

        if (!file_exists($filename)) {
            throw new Exception('Config file does not exist');
        }

        return file_get_contents($filename);
    }
}
