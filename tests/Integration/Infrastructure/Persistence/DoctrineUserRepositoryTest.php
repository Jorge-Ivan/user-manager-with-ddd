<?php
namespace Tests\Integration\Infrastructure\Persistence;

use UserManager\Domain\Model\User;
use UserManager\Domain\Model\ValueObject\Email;
use UserManager\Domain\Model\ValueObject\Name;
use UserManager\Domain\Model\ValueObject\Password;
use UserManager\Domain\Model\ValueObject\UserId;
use UserManager\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use UserManager\Infrastructure\Persistence\Doctrine\Type\UserIdType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use PHPUnit\Framework\TestCase;

class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__ . '/../../../../src'],
            true
        );

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ]);

        if (!Type::hasType('app_user_id')) {
            Type::addType('app_user_id', UserIdType::class);
        }

        $connection->getDatabasePlatform()->registerDoctrineTypeMapping('app_user_id', 'string');

        $this->entityManager = EntityManager::create($connection, $config);

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($classes);

        $this->repository = new DoctrineUserRepository($this->entityManager);
    }

    public function testSaveAndFindById(): void
    {
        $userId = UserId::generate();
        $name = Name::fromString('John Doe');
        $email = Email::fromString('john.doe@example.com');
        $password = Password::fromPlainPassword('Password123!');
        
        $user = User::register($userId, $name, $email, $password);
        
        $this->repository->save($user);
        
        $this->entityManager->clear();
        
        $foundUser = $this->repository->findById($userId);
        
        $this->assertNotNull($foundUser);
        $this->assertTrue($userId->equals($foundUser->id()));
        $this->assertTrue($name->equals($foundUser->name()));
        $this->assertTrue($email->equals($foundUser->email()));
    }

    public function testFindByEmail(): void
    {
        $userId = UserId::generate();
        $name = Name::fromString('Jane Doe');
        $email = Email::fromString('jane.doe@example.com');
        $password = Password::fromPlainPassword('Password123!');
        
        $user = User::register($userId, $name, $email, $password);
        
        $this->repository->save($user);
        
        $this->entityManager->clear();
        
        $foundUser = $this->repository->findByEmail($email);
        
        $this->assertNotNull($foundUser);
        $this->assertTrue($userId->equals($foundUser->id()));
    }

    public function testDelete(): void
    {
        $userId = UserId::generate();
        $name = Name::fromString('Alice Smith');
        $email = Email::fromString('alice.smith@example.com');
        $password = Password::fromPlainPassword('Password123!');
        
        $user = User::register($userId, $name, $email, $password);
        
        $this->repository->save($user);
        
        $foundUser = $this->repository->findById($userId);
        $this->assertNotNull($foundUser);
        
        $this->repository->delete($userId);
        
        $deletedUser = $this->repository->findById($userId);
        $this->assertNull($deletedUser);
    }
}
