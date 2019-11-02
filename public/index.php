<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$loader->addPsr4('Core\\', 'Core/');
$loader->addPsr4('Modules\\', 'Modules/');
$loader->addPsr4('Model\\', 'Model/');

use Core\Router;
use Core\Di;

new Modules\Main\Controller;
new Modules\API\Controller;

$router = Router::getInstance();
$router->start();
