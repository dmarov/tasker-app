<?php

namespace Core\Db;

class Connection {

    protected static $instance = null;
    private $pdo;

    public function __construct() {

        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        if ($port === false) $port = '3306';
        $user = getenv('DB_USER');
        $password = getenv('DB_PASSWORD');
        $db = getenv('DB_NAME');
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
