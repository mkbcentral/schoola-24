<?php

namespace App\DTOs\Auth;

class LoginDTO
{
    public function __construct(
        public readonly string $identifier, // username ou email
        public readonly string $password,
        public readonly bool $remember = false,
        public readonly ?string $ipAddress = null,
        public readonly ?string $userAgent = null
    ) {}

    /**
     * Créer un DTO depuis un tableau de données
     */
    public static function fromArray(array $data): self
    {
        return new self(
            identifier: $data['identifier'] ?? '',
            password: $data['password'] ?? '',
            remember: $data['remember'] ?? false,
            ipAddress: $data['ip_address'] ?? null,
            userAgent: $data['user_agent'] ?? null
        );
    }

    /**
     * Convertir en tableau pour les credentials Laravel Auth
     */
    public function toCredentials(): array
    {
        // Déterminer si l'identifiant est un email ou un username
        $field = filter_var($this->identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $this->identifier,
            'password' => $this->password,
        ];
    }

    /**
     * Vérifier si le DTO est valide
     */
    public function isValid(): bool
    {
        return !empty($this->identifier) && !empty($this->password);
    }
}
