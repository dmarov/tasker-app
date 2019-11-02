<?php

namespace Modules\API\Factory;

class QueryParams {

    static function getOffset($default = 0) {

        $offset = isset($_GET['offset']) ? $_GET['offset'] : $default;
        return (Integer) $offset;
    }

    static function getLimit($default = 30, $max = 1000) {

        $limit = isset($_GET['limit']) ? min($_GET['limit'], $max) : min($default, $max);
        return (Integer)$limit;
    }

    static function getTasksOrder() {

        return [ 'username' => 'ASC' ];
    }
}
