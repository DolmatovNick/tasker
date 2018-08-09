<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = ["App/Models/Entity"];
$isDevMode = true;

// the connection configuration
$dbParams = [
    'host'     => 'localhost',
    'port'     => '5433',
    'user'     => 'postgres',
    'password' => 'pass',
    'dbname'   => 'postgres2',
    'driver'   => 'pdo_pgsql',
    'charset' => 'utf8',
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
return EntityManager::create($dbParams, $config);