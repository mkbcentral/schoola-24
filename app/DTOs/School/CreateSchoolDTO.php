<?php

namespace App\DTOs\School;

class CreateSchoolDTO
{
    public function __construct(
        public string $name,
        public string $type,
        public string $email,
        public string $phone,
        public ?string $logo = null,
        public string $adminName = '',
        public string $adminUsername = '',
        public string $adminEmail = '',
        public string $adminPhone = '',
        public bool $isActive = true,
        public ?string $subscriptionStart = null,
        public ?string $subscriptionEnd = null,
        public ?array $settings = null,
    ) {}

    /**
     * Créer un DTO depuis un tableau de données de formulaire
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            type: $data['type'] ?? '',
            email: $data['email'] ?? '',
            phone: $data['phone'] ?? '',
            logo: $data['logo'] ?? null,
            adminName: $data['admin_name'] ?? '',
            adminUsername: $data['admin_username'] ?? '',
            adminEmail: $data['admin_email'] ?? '',
            adminPhone: $data['admin_phone'] ?? '',
            isActive: $data['is_active'] ?? true,
            subscriptionStart: $data['subscription_start'] ?? null,
            subscriptionEnd: $data['subscription_end'] ?? null,
            settings: $data['settings'] ?? null,
        );
    }

    /**
     * Convertir le DTO en tableau pour la création de l'école
     */
    public function toSchoolArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'email' => $this->email,
            'phone' => $this->phone,
            'logo' => $this->logo,
            'app_status' => $this->isActive ? 'active' : 'inactive',
            'school_status' => $this->isActive ? 'active' : 'inactive',
        ];
    }

    /**
     * Convertir le DTO en tableau pour la création de l'admin
     */
    public function toAdminArray(): array
    {
        return [
            'name' => $this->adminName,
            'username' => $this->adminUsername,
            'email' => $this->adminEmail,
            'phone' => $this->adminPhone,
        ];
    }

    /**
     * Valider les données
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'Le nom de l\'école est requis.';
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'L\'email de l\'école est invalide.';
        }

        if (empty($this->adminName)) {
            $errors['admin_name'] = 'Le nom de l\'administrateur est requis.';
        }

        if (empty($this->adminUsername)) {
            $errors['admin_username'] = 'Le nom d\'utilisateur de l\'administrateur est requis.';
        }

        if (empty($this->adminEmail) || !filter_var($this->adminEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['admin_email'] = 'L\'email de l\'administrateur est invalide.';
        }

        return $errors;
    }
}
