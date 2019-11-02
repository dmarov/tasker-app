<?php

namespace Modules\API\Factory\HAL;

use Core\Router;

class Message {

    public static function getLinks($params) {

        $item = $params['item'];
        $router = Router::getInstance();
        $routes = [
            'self' => $router->getAbsolutePath('api:message', [
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
            'name' => $item->name,
            'surname' => $item->surname,
            'patronymic' => $item->patronymic,
            'message' => $item->message,
            'creationDate' => $item->creation_date,
            '_links' => self::getLinks([
                'item' => $item,
            ]),
            '_embedded' => self::getEmbedded([
                'item' => $item,
            ]),
        ];
    }
}
