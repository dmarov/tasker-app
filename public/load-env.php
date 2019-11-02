<?php
$envFile = __DIR__ ."/configs/env.json";

if (file_exists($envFile)) {

    $env = file_get_contents($envFile);
    $env = json_decode($env, true);

    if (is_array($env)) {

        foreach ($env as $key => $value)
            define($key, $value);
    }
}

