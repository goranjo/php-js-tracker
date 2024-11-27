<?php

namespace App\Core\Factory;

use Exception;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Dotenv\Dotenv;

class ContainerFactory
{
    /**
     * @throws Exception
     */
    public static function create(): ContainerBuilder
    {
        $projectRoot = dirname(__DIR__, 4);
        $dotenvPath = $projectRoot . '/.env';

        if (!file_exists($dotenvPath)) {
            throw new RuntimeException("The .env is was not found: $dotenvPath");
        }

        $dotenv = Dotenv::createImmutable($projectRoot);
        $dotenv->load();

        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', $_ENV['APP_DEBUG']);
        $container->setParameter('kernel.cache_dir', $projectRoot . '/var/cache');

        $loader = new YamlFileLoader($container, new FileLocator($projectRoot . '/src/App/config'));
        $loader->load('services.yaml');
        $container->compile();

        return $container;
    }
}
