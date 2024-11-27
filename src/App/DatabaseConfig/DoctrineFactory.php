<?php

namespace App\DatabaseConfig;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class DoctrineFactory
{
    public static function create(array $config, FilesystemAdapter $cache): EntityManager
    {
        $proxyDir = __DIR__ . '/../../var/cache';

        $doctrineConfig = ORMSetup::createAttributeMetadataConfiguration(
            paths: [],
            isDevMode: true,
            proxyDir: $proxyDir,
            cache: $cache
        );

        $connectionParams = [
            'dbname' => $config['db']['dbname'],
            'user' => $config['db']['user'],
            'password' => $config['db']['password'],
            'host' => $config['db']['host'],
            'driver' => $config['db']['driver'],
        ];

        $connection = DriverManager::getConnection($connectionParams, $doctrineConfig);

        return new EntityManager($connection, $doctrineConfig);
    }
}
