<?php

namespace Modules\Main;

class Controller {

    public static function getIndex($ctx) {

        $router = \Core\Router::getInstance();
        $path = $router->getAbsolutePath('api:messages');

        ob_start();
        include __DIR__ . '/html/index.php';
        $res = ob_get_contents();
        ob_end_clean();
        die($res);
    }
}
