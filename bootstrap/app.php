<?php

require 'autoloader.php';

use App\Helpers\Config;
use App\Database\Connection;
use App\Database\QueryBuilder;

function app() {
    $app = [];
    $app['config'] = new Config();
    $app['conn'] = Connection::make($app['config']);
    $app['db'] = new QueryBuilder($app['conn']);

    $app = (object) $app;

    return $app;
}
