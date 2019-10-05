<?php

namespace App\Helpers;

use Exception;

class Config
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = empty($config) ? $this->getConfig() : $config;

        return $this;
    }

    /**
     * Get config like accessing a property
     *
     * @param string $name
     * @return mixed 
     */
    public function __get(string $name)
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
            return new static($value);
        }

        return $value;
    }

    /**
     * Get config from config file
     *
     * @return array 
     */
    private function getConfig()
    {
        return json_decode($this->getConfigFile(), true);
    }

    /**
     * Get config file content
     *
     * @return string 
     */
    private function getConfigFile()
    {
        $filename = __DIR__ . '/../config.json';

        if (!file_exists($filename)) {
            throw new Exception('Config file does not exist at '. $filename);
        }

        return file_get_contents($filename);
    }
}
