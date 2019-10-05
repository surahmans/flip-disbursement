<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Helpers\Config;

class Connection
{
    /**
     * Make a connection
     *
     * @param Config $config
     * @return PDO
     */
    public static function make(Config $config)
    {
        try {
            return new PDO(
                sprintf('%s:host=%s;dbname=%s', strtolower($config->database->driver), $config->database->host, $config->database->name), 
                $config->database->user,
                $config->database->pass, 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
