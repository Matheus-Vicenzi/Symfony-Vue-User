<?php

namespace App\VOs;

use InvalidArgumentException;

final readonly class Email
{
    private string $value;

    public function __construct(string $email)
    {
        if (!$this->isValid($email)) {
            throw new InvalidArgumentException("Email '{$email}' é inválido.");
        }

        $this->value = strtolower(trim($email));
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isValid(string $email): bool
    {
        $email = trim($email);
        if (empty($email)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (str_contains($email, ' ')) {
            return false;
        }

        if (strlen($email) > 254) {
            return false;
        }

        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return false;
        }

        [$localPart, $domain] = $parts;

        if (empty($localPart) || strlen($localPart) > 64) {
            return false;
        }

        if (!str_contains($domain, '.') || strlen($domain) < 4) {
            return false;
        }

        return true;
    }

    public function getDomain(): string
    {
        return explode('@', $this->value)[1];
    }

    public function getLocalPart(): string
    {
        return explode('@', $this->value)[0];
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __debugInfo(): array
    {
        return ['value' => $this->value];
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public static function isValidEmail(string $email): bool
    {
        try {
            new self($email);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }
}