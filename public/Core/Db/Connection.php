<?php

namespace Core\Db;

class Connection {

    protected static $instance = null;
    private $pdo;

    public function __construct() {

        $host = getenv('PG_HOST');
        $port = getenv('PG_PORT');
        if ($port === false) $port = '5432';
        $user = getenv('PG_USER');
        $db = getenv('PG_DATABASE');
        $password = getenv('PG_PASSWORD');
        $dsn = "pgsql:host=${host};port=${port};dbname=${db};user=${user};password=${password}";
        $pdo = new \PDO($dsn);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }

    public static function getInstance() {

        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function getPDO() {

        return $this->pdo;
    }
}
