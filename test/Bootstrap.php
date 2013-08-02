<?php

use Zend\Loader\StandardAutoloader;

require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL | E_STRICT);

$autoloaderConfig = array(
    'namespaces' => array(
        'TillikumTest' => __DIR__ . '/TillikumTest',
    ),
);

$autoloader = new StandardAutoloader($autoloaderConfig);
$autoloader->register();

unset($autoloaderConfig);
unset($autoloader);
