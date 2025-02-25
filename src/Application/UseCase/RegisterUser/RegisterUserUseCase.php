<?php
namespace UserManager\Application\UseCase\RegisterUser;

use UserManager\Application\DTO\UserResponseDTO;
use UserManager\Application\Event\EventBus;
use UserManager\Domain\Exception\UserAlreadyExistsException;
use UserManager\Domain\Model\User;
use UserManager\Domain\Model\ValueObject\Email;
use UserManager\Domain\Model\ValueObject\Name;
use UserManager\Domain\Model\ValueObject\Password;
use UserManager\Domain\Model\ValueObject\UserId;
use UserManager\Domain\Repository\UserRepositoryInterface;

final class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private EventBus $eventBus;

    public function __construct(UserRepositoryInterface $userRepository, EventBus $eventBus)
    {
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
    }

    public function execute(RegisterUserRequest $request): UserResponseDTO
    {
        $email = Email::fromString($request->email());
        
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser !== null) {
            throw new UserAlreadyExistsException('A user with this email already exists');
        }

        $user = User::register(
            UserId::generate(),
            Name::fromString($request->name()),
            $email,
            Password::fromPlainPassword($request->password())
        );

        $this->userRepository->save($user);

        foreach ($user->pullEvents() as $event) {
            $this->eventBus->dispatch($event);
        }

        return UserResponseDTO::fromUser($user);
    }
}