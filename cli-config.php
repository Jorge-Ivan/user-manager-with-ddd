<?php

require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Symfony\Component\Dotenv\Dotenv;
use UserManager\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use UserManager\Infrastructure\Persistence\Doctrine\Type\UserIdType;

// Loading environment variables
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

// Doctrine Configuration
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . '/src'],
    true
);

// Connecting to the database
$connectionParams = [
    'driver' => 'pdo_mysql',
    'url' => $_ENV['DATABASE_URL'],
];

$connection = DriverManager::getConnection($connectionParams, $config);

// Register the custom type only if it is not already registered
if (!Type::hasType('app_user_id')) {
    Type::addType('app_user_id', UserIdType::class);
}

// Creating EntityManager correctly
$entityManager = EntityManager::create($connection, $config);

// Return Doctrine console configuration
return ConsoleRunner::createHelperSet($entityManager);
