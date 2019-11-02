<?php

namespace Modules\API\Factory\HAL;

use Core\Router;

class Task {

    public static function getLinks($params) {

        $item = $params['item'];
        $router = Router::getInstance();
        $routes = [
            'self' => $router->getAbsolutePath('api:task', [
                'id' => $item->id
            ]),
        ];

        return (Object)[
            'self' => (Object)[
                'href' => $routes['self'],
            ]
        ];
    }

    public static function getEmbedded($params) {

        return (object)null;
    }

    public static function get($params) {

        $item = $params['item'];

        return (Object)[
            'id' => $item->id,
            'username' => $item->username,
            'email' => $item->email,
            'text' => $item->text,
            'edited' => $item->edited,
            '_links' => self::getLinks([
                'item' => $item,
            ]),
            '_embedded' => self::getEmbedded([
                'item' => $item,
            ]),
        ];
    }
}
