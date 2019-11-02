<?php
$envFile = __DIR__ ."/../configs/env.yml";

if (file_exists($envFile)) {

    $env = file_get_contents($envFile);
    $env = yaml_parse($env);

    if (is_array($env)) {

        foreach ($env as $key => $value)
            putenv("${key}=${value}");
    }
}

