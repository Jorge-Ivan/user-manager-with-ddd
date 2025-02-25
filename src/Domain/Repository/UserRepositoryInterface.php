<?php
namespace UserManager\Domain\Repository;

use UserManager\Domain\Model\User;
use UserManager\Domain\Model\ValueObject\Email;
use UserManager\Domain\Model\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;
    public function findById(UserId $id): ?User;
    public function findByEmail(Email $email): ?User;
    public function delete(UserId $id): void;
}