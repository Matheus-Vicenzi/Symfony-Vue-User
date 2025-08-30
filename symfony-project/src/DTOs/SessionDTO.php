<?php

namespace App\DTOs;

final readonly class SessionDTO
{
    public function __construct(
        private int $id,
        private string $email,
        private string $name,
        private \DateTimeImmutable $loggedInAt
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLoggedInAt(): \DateTimeImmutable
    {
        return $this->loggedInAt;
    }
}