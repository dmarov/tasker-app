<?php

namespace Modules\Main;

class Controller {

    private $di;

    public function __construct() {

        $router = \Core\Router::getInstance();

        $router->add('GET', '/', [$this, 'getMain']);
    }

    public static function getMain($ctx) {

        $router = \Core\Router::getInstance();
        $path = $router->getAbsolutePath('api:messages');

        ob_start();
        include __DIR__ . '/html/index.php';
        $res = ob_get_contents();
        ob_end_clean();
        die($res);
    }
}
