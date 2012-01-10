<?php

require_once __DIR__.'/../vendor/Silex/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../app/views',
	'twig.class_path' => __DIR__.'/../vendor/Twig/lib',
	'twig.options' => array('cache' => __DIR__. '/../data/cache/twig')
));


$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options'            => array(
        'driver'    => 'pdo_mysql',
	'host' => 'localhost',
	'dbname' => 'oncall',
	'user' => 'root',
	'password' => 'root'
    ),
    'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine2/lib/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__.'/../vendor/doctrine2/lib/vendor/doctrine-common/lib',
));

/*
$app['autoloader']->registerNamespace('Nutwerk', __DIR__.'/../vendor/nutwerk-orm-extension/lib');
$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.class_path'            => __DIR__.'/../vendor/doctrine2/lib',
    'db.orm.proxies_dir'           => __DIR__.'/../data/cache/doctrine/Proxy',
    'db.orm.proxies_namespace'     => 'DoctrineProxy',
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type'      => 'annotation', 
        'path'      => __DIR__.'/../app', 
        'namespace' => 'Entity',
    )),
));
*/


$app->get('/', function() use ($app) {
    return "this is the index...";
});

$app->get('/hello/{name}', function ($name) use ($app) {
	$data = $app['twig']->render('hello.twig', array('name' => $name));
	return $data;
});

$app->get('/users', function() use ($app) {
	$sql = "select * from user";
	$users = $app['db']->fetchAll($sql);
	return $app['twig']->render('users.twig', array('users' => $users));
});

$app->run();
