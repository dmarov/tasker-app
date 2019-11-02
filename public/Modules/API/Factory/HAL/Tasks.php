<?php

namespace Modules\API\Factory\HAL;

use Core\Router;

class Tasks {

    public static function getLinks($params) {

        $router = Router::getInstance();
        $selfPath = $router->getAbsolutePath('api:tasks');

        $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];

        parse_str($query, $queryObj);
        $queryObj = (Object)$queryObj;

        $queryStr = http_build_query($queryObj);
        $selfUrl = $selfPath . ($queryStr ? '?' . $queryStr : '');

        $queryObj->offset = $offset - ceil($offset / max($limit, 1)) * $limit;
        $queryObj->limit = $limit;
        $queryStr = http_build_query($queryObj);
        $firstUrl = $selfPath . ($queryStr ? '?' . $queryStr : '');

        $queryObj->offset = $offset + floor(($total - $offset) / max($limit, 1)) * $limit;
        $queryObj->limit = $limit;
        $queryStr = http_build_query($queryObj);
        $lastUrl = $selfPath . ($queryStr ? '?' . $queryStr : '');

        $result = (Object)[
            'self' => (Object)[
                'href' => $selfUrl,
            ],
            'first' => (Object)[
                'href' => $firstUrl,
            ],
            'last' => (Object)[
                'href' => $lastUrl,
            ],
        ];

        if ($offset + $limit < $total) {

            $queryObj->offset = $offset + $limit;
            $queryObj->limit = $limit;
            $queryStr = http_build_query($queryObj);
            $nextUrl = $selfPath . ($queryStr ? '?' . $queryStr : '');

            $result->next = (Object)[
                'href' => $nextUrl,
            ];
        }

        if ($offset - $limit >= 0) {

            $queryObj->offset = $offset - $limit;
            $queryObj->limit = $limit;
            $queryStr = http_build_query($queryObj);
            $prevUrl = $selfPath . ($queryStr ? '?' . $queryStr : '');

            $result->prev = (Object)[
                'href' => $prevUrl,
            ];
        }

        return $result;
    }

    public static function getEmbedded($params) {

        $items = $params['items'];
        $messages = [];

        foreach ($items as $item) {
            $messages[] = Task::get([
                'item' => $item,
            ]);
        }

        return (Object)[
            'items' => $messages,
        ];
    }

    public static function get($params) {

        $items = $params['items'];
        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];
        $count = count($items);

        return (Object)[
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
            'count' => $count,
            '_links' => self::getLinks([
                'offset' => $offset,
                'limit' => $limit,
                'total' => $total,
                'items' => $items,
            ]),
            '_embedded' => self::getEmbedded([
                'items' => $items,
            ]),
        ];
    }
}
