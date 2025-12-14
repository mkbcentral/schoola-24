<?php

namespace App\DTOs\Configuration;

class SchoolYearDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public bool $isActive = false,
        public ?int $schoolId = null,
        public ?int $userId = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    /**
     * Créer un DTO depuis un modèle SchoolYear
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            isActive: $model->is_active,
            schoolId: $model->school_id,
            userId: $model->user_id,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString(),
        );
    }

    /**
     * Créer un DTO depuis un tableau de données
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? '',
            isActive: $data['is_active'] ?? false,
            schoolId: $data['school_id'] ?? null,
            userId: $data['user_id'] ?? null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }

    /**
     * Convertir le DTO en tableau
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'is_active' => $this->isActive,
            'school_id' => $this->schoolId,
            'user_id' => $this->userId,
        ];

        if ($this->id !== null) {
            $data['id'] = $this->id;
        }

        return $data;
    }

    /**
     * Valider les données du DTO
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = 'Le nom de l\'année scolaire est requis';
        }

        if ($this->schoolId === null) {
            $errors['school_id'] = 'L\'école est requise';
        }

        return $errors;
    }
}
