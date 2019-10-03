<?php

spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $file = __DIR__ . '/../' . str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});
