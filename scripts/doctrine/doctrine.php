<?php

define('APPLICATION_PATH', realpath(__DIR__ .'/../../app'));
define('VENDOR_PATH', realpath(__DIR__.'/../../vendor'));
define('DOCTRINE_PATH', VENDOR_PATH . '/doctrine2/lib');

require_once DOCTRINE_PATH . '/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', DOCTRINE_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', DOCTRINE_PATH . '/vendor/doctrine-dbal/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', DOCTRINE_PATH . '/vendor/doctrine-common/lib');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', DOCTRINE_PATH . '/vendor');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entitiy', APPLICATION_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath(APPLICATION_PATH . '/../data/cache/doctrine/proxies'));
$classLoader->register();

// Variable $helperSet is defined inside cli-config.php
require __DIR__ . '/cli-config.php';

$cli = new \Symfony\Component\Console\Application('Doctrine Command Line Interface', Doctrine\Common\Version::VERSION);
$cli->setCatchExceptions(true);
$helperSet = $cli->getHelperSet();
foreach ($helpers as $name => $helper) {
    $helperSet->set($helper, $name);
}
$cli->addCommands(array(
    // DBAL Commands
    new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
    new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),

    // ORM Commands
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
    new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
    new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
    new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
    new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),

));
$cli->run();
