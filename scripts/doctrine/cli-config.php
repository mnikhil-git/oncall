<?php

require_once DOCTRINE_PATH . '/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', DOCTRINE_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', DOCTRINE_PATH . '/vendor/doctrine-dbal/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', DOCTRINE_PATH . '/vendor/doctrine-common/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', DOCTRINE_PATH . '/vendor');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entity', APPLICATION_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath(APPLICATION_PATH . '/../data/cache/doctrine'));
$classLoader->register();


$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(APPLICATION_PATH . "/Entity"));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(realpath(APPLICATION_PATH . '/../data/cache/doctrine/Proxies'));
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'oncall',
    'user' => 'root',
    'password' => 'root'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);