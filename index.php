<?php

require_once 'vendor/autoload.php';

use UserManager\Application\Event\WelcomeEmailSender;
use UserManager\Application\UseCase\RegisterUser\RegisterUserUseCase;
use UserManager\Domain\Event\UserRegisteredEvent;
use UserManager\Infrastructure\Controller\RegisterUserController;
use UserManager\Infrastructure\Event\SimpleEventBus;
use UserManager\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use UserManager\Infrastructure\Persistence\Doctrine\Type\UserIdType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . '/src'],
    true
);

$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'url' => $_ENV['DATABASE_URL'],
]);

$entityManager = new EntityManager($connection, $config);

if (!Type::hasType('app_user_id')) {
    Type::addType('app_user_id', UserIdType::class);
}

$userRepository = new DoctrineUserRepository($entityManager);

$eventBus = new SimpleEventBus();
$welcomeEmailSender = new WelcomeEmailSender();
$eventBus->register(UserRegisteredEvent::class, $welcomeEmailSender);

$registerUserUseCase = new RegisterUserUseCase($userRepository, $eventBus);

$registerUserController = new RegisterUserController($registerUserUseCase);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

header('Content-Type: application/json');

if ($requestMethod === 'POST' && $requestUri === '/api/register') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $response = $registerUserController($requestData);
    
    http_response_code($response['code']);
    echo json_encode($response);
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'code' => 404,
        'message' => 'Endpoint not found'
    ]);
}