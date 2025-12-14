<?php

namespace App\DTOs\School;

class UpdateSchoolDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $email,
        public string $phone,
        public ?string $logo = null,
        public bool $isActive = true,
        public ?string $subscriptionStart = null,
        public ?string $subscriptionEnd = null,
        public ?array $settings = null,
    ) {}

    /**
     * Créer un DTO depuis un modèle School
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            type: $model->type,
            email: $model->email,
            phone: $model->phone,
            logo: $model->logo,
            isActive: $model->app_status === 'active',
        );
    }

    /**
     * Créer un DTO depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            name: $data['name'] ?? '',
            type: $data['type'] ?? '',
            email: $data['email'] ?? '',
            phone: $data['phone'] ?? '',
            logo: $data['logo'] ?? null,
            isActive: $data['is_active'] ?? true,
            subscriptionStart: $data['subscription_start'] ?? null,
            subscriptionEnd: $data['subscription_end'] ?? null,
            settings: $data['settings'] ?? null,
        );
    }

    /**
     * Convertir le DTO en tableau pour la mise à jour
     */
    public function toArray(): array
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
     * Valider les données
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'Le nom de l\'école est requis.';
        }

        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'L\'email est invalide.';
        }

        return $errors;
    }
}
