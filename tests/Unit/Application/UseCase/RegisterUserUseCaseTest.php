<?php
namespace Tests\Unit\Application\UseCase;

use UserManager\Application\Event\EventBus;
use UserManager\Application\UseCase\RegisterUser\RegisterUserRequest;
use UserManager\Application\UseCase\RegisterUser\RegisterUserUseCase;
use UserManager\Domain\Exception\UserAlreadyExistsException;
use UserManager\Domain\Model\User;
use UserManager\Domain\Model\ValueObject\Email;
use UserManager\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class RegisterUserUseCaseTest extends TestCase
{
    private $userRepository;
    private $eventBus;
    private $useCase;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(EventBus::class);
        $this->useCase = new RegisterUserUseCase($this->userRepository, $this->eventBus);
    }

    public function testExecuteWithNewUser(): void
    {
        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('save');

        $this->eventBus->expects($this->once())
            ->method('dispatch');

        $request = new RegisterUserRequest(
            'John Doe',
            'john.doe@example.com',
            'Password123!'
        );

        $response = $this->useCase->execute($request);

        $this->assertEquals('John Doe', $response->jsonSerialize()['name']);
        $this->assertEquals('john.doe@example.com', $response->jsonSerialize()['email']);
    }

    public function testExecuteWithExistingUser(): void
    {
        $existingUser = $this->createMock(User::class);

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->willReturn($existingUser);

        $this->userRepository->expects($this->never())
            ->method('save');

        $request = new RegisterUserRequest(
            'John Doe',
            'john.doe@example.com',
            'Password123!'
        );

        $this->expectException(UserAlreadyExistsException::class);
        $this->useCase->execute($request);
    }
}