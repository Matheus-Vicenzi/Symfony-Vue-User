<?php

namespace App\Service;

use App\DTOs\UserInputDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }

    public function createUser(UserInputDTO $data): User
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $data->getEmail()]);
        if ($existingUser) {
            throw new \DomainException('Usuário com esse email já existe.');
        }

        $user = new User(
            $data->getName(),
            $data->getEmail(),
            $data->getPassword()
        );

        $hashedPassword = $this->passwordHasher->hashPassword($user, $data->getPassword());
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);

        return $user;
    }
}