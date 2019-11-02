<?php

namespace Core;

class Router {

    private $routes = [];
    private $paths = [];

    protected static $instance = null;

    public static function getInstance() {

        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function start() {

        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {

            $path = $route->path;
            $content = preg_quote($path, '/');

            $pathReg = "/^${content}$/";
            $pathReg = preg_replace('/\\\:([a-zA-Z0-9]+)/', '(?<_$1>[^\/]+)', $pathReg);
            $function = $route->function;

            $matches = [];

            if (preg_match($pathReg, $requestPath, $matches) && $route->method === $_SERVER['REQUEST_METHOD']) {

                $ctx = new \stdClass;
                $ctx->params = new \stdClass;

                foreach($matches as $key => $value) {

                    $keyMatches = [];
                    if (preg_match('/_([a-zA-Z0-9]+)/', $key, $keyMatches))
                        $ctx->params->{$keyMatches[1]} = $value;
                }

                try {

                    $function($ctx);
                } catch (Exceptions\HTTP $e) {

                    header('Content-Type: application/json');
                    http_response_code($e->getHttpCode());
                    die(json_encode([
                        'errors' => [
                            [
                                'message' => $e->getMessage(),
                                'httpCode' => $e->getHttpCode(),
                            ],
                        ],
                    ]));

                } catch (\Exception $e) {

                    header('Content-Type: application/json');
                    http_response_code(500);

                    if (getenv("DEBUG")) {

                        die(json_encode([
                            'errors' => [
                                [
                                    'message' => $e->getMessage(),
                                    'httpCode' => 500,
                                    'trace' => $e->getTrace(),
                                    'file' => $e->getFile(),
                                    'line' => $e->getLine(),
                                ],
                            ],
                        ]));
                    } else {

                        die(json_encode([
                            'errors' => [
                                [
                                    'message' => $e->getMessage(),
                                    'httpCode' => 500,
                                ],
                            ],
                        ]));
                    }
                }
            }
        }
    }

    public function add($method, $path, $function, $name = null) {

        $route = new \stdClass;
        $route->method = $method;
        $route->path = $path;
        $route->function = $function;
        $this->routes[] = $route;

        if (is_string($name)) {
            $this->paths[$name] = $path;
        }
    }

    public function getPath($name, $params = []) {

        $path = $this->paths[$name] ?? null;

        if (is_string($path)) {

            foreach($params as $key => $value) {

                $path = preg_replace("/\:${key}/", $value, $path);
            }

            return $path;

        } else return null;
    }

    public function getAbsolutePath($name, $params = []) {

        $path = $this->getPath($name, $params);
        $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || getenv("HTTPS") === "on" ? 'https' : 'http';
        return $path ? $proto . '://' . $_SERVER['HTTP_HOST'] . $path : null;
    }
}
