<?php

namespace Core\Db;

class Connection {

    protected static $instance = null;
    private $pdo;

    public function __construct() {

        $host = DB_HOST;
        $port = DB_PORT;
        if ($port === false) $port = '3306';
        $user = DB_USER;
        $password = DB_PASSWORD;
        $db = DB_NAME;
        $dsn = "mysql:host=${host};port=${port};dbname=${db}";
        $pdo = new \PDO($dsn, $user, $password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }

    public static function getInstance() {

        if (!isset(static::$instance))
            static::$instance = new static;

        return static::$instance;
    }

    public function getPDO() {

        return $this->pdo;
    }
}
