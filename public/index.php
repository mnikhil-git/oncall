<?php

require_once __DIR__.'/../vendor/Silex/silex.phar';

$app = new Silex\Application();

$app->get('/', function() use ($app) {
    return "this is the index...";
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello, ' . $app->escape($name);
});

$app->run();