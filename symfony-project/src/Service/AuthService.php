<?php

namespace App\Service;

use App\DTOs\LoginInputDTO;
use App\DTOs\SessionDTO;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private RequestStack $requestStack,
        private LoggerInterface $logger
    ) {}

    public function authenticate(LoginInputDTO $loginDTO): SessionDTO
    {
        $user = $this->userRepository->findOneBy(['email' => $loginDTO->getEmail()]);
        
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $loginDTO->getPassword())) {
            throw new \InvalidArgumentException('Email ou senha inválidos.');
        }

        $session = $this->requestStack->getSession();
        $session->set('user_id', $user->getId());
        $session->set('user_email', $user->getEmail());
        $session->set('user_name', $user->getName());
        $session->set('logged_in_at', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

        return new SessionDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getName(),
            new \DateTimeImmutable($session->get('logged_in_at'))
        );
    }

    public function getCurrentUser(): ?array
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('user_id');
        $this->logger->info('Session data', [
            'user_id' => $session->get('user_id'),
            'user_email' => $session->get('user_email'),
            'user_name' => $session->get('user_name'),
            'logged_in_at' => $session->get('logged_in_at')
        ]);

        return $userId ? [
            'id' => $userId,
            'name' => $session->get('user_name'),
            'email' => $session->get('user_email'),
            'logged_in_at' => $session->get('logged_in_at')
        ] : null;
    }
}