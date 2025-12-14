<?php

namespace App\DTOs\Configuration;

class SectionDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public ?int $schoolId = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    /**
     * Créer un DTO depuis un modèle Section
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            schoolId: $model->school_id,
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
            schoolId: $data['school_id'] ?? null,
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
            'school_id' => $this->schoolId,
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
            $errors['name'] = 'Le nom de la section est requis';
        }

        if ($this->schoolId === null) {
            $errors['school_id'] = 'L\'école est requise';
        }

        return $errors;
    }
}
