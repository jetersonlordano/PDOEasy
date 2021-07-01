<?php

define('DS', DIRECTORY_SEPARATOR);
define('SOURCE', dirname(__DIR__) . DS . 'source');

define('DATA_BASE', [
    'host' => 'localhost',
    'db' => 'pdoeasy',
    'port' => '3306',
    'user' => 'root',
    'psw' => '',
]);

// Auto loader de classes
spl_autoload_register(function ($class) {
    $class = str_replace([DS . DS, '//', '\\'], DS, SOURCE . DS . "{$class}.class.php");
    if (file_exists($class)) {
        include_once $class;
    };
});
