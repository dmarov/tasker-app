<?php

namespace Core;

class Di {

    protected static $instance = null;
    private static $services = [];

    public static function getInstance() {

        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function add($key, $value) {

        $this->services[$key] = $value;
    }

    public function get($key) {

        return static::services[$key] ?? null;
    }
}
