<?php

require_once __DIR__ . '/load-env.php';
$loader = require_once __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$loader->addPsr4('Core\\', 'Core/');
$loader->addPsr4('Modules\\', 'Modules/');
$loader->addPsr4('Model\\', 'Model/');

use Core\Router;

$main = new Modules\Main\Controller;
$api = new Modules\API\Controller;

$router = Router::getInstance();

$router->add("GET", "/", [ $main, 'getIndex' ]);
$router->add("GET", "/api/tasks", [ $api, 'getTasks' ], "api:tasks");
$router->add("GET", "/api/tasks/:id", [ $api, 'getTask' ], "api:task");
$router->add("POST", "/api/tasks", [ $api, 'appendTask' ]);
$router->add("PATCH", "/api/tasks/:id", [ $api, 'patchTask' ]);
$router->start();
