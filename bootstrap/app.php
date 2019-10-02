<?php

require 'autoloader.php';

use App\Helpers\Config;

$app = [];
$app['config'] = new Config();

$app = (object) $app;
