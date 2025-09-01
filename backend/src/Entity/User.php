<?php

namespace App\Entity;

use App\VOs\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function __construct(string $name, Email $email, string $password)
    {
        parent::__construct(); // Chama o construtor da BaseEntity
        
        $this->name = $name;
        $this->email = $email->getValue();
        $this->password = $password;
        $this->isValid();
    }

    private function isValidName(string $name): bool
    {
        return !empty($name) && strlen($name) <= 255;
    }

    private function isValidPassword(string $password): bool
    {
        return !empty($password) && strlen($password) >= 8 && strlen($password) <= 255;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?Email
    {
        return new Email($this->email);
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function isValid(): void
    {
        if (!$this->isValidName($this->name)) {
            throw new \InvalidArgumentException('Nome inválido, necessário ter entre 1 e 255 caracteres.');
        }

        if (!Email::isValidEmail($this->email)) {
            throw new \InvalidArgumentException('Email inválido.');
        }

        if (!$this->isValidPassword($this->password)) {
            throw new \InvalidArgumentException('Senha inválida, necessário ter entre 8 e 255 caracteres.');
        }
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Limpar dados sensíveis temporários se houver
        return;
    }

    public function toString(): string|null
    {
        return $this->name;
    }
}