<?php



require_once __DIR__ . '/../vendor/Silex/autoload.php';

$app = new Silex\Application();

include __DIR__ . '/../app/Bootstrap.php';
include __DIR__ . '/../app/Controllers/main.php';

$app->run();
