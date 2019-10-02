<?php

require 'autoloader.php';

use App\Helpers\Config;
use App\Database\Connection;

$app = [];
$app['config'] = new Config();
$app['conn'] = Connection::make($app['config']);

$app = (object) $app;
