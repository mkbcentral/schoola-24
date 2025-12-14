<?php

namespace App\DTOs\Fee;

class CategoryRegistrationFeeDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public bool $isOld = false,
        public ?int $schoolId = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    /**
     * Créer un DTO depuis une requête HTTP
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? '',
            isOld: $data['is_old'] ?? false,
            schoolId: $data['school_id'] ?? null,
        );
    }

    /**
     * Créer un DTO depuis un modèle
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            isOld: $model->is_old,
            schoolId: $model->school_id,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString(),
        );
    }

    /**
     * Convertir le DTO en tableau
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_old' => $this->isOld,
            'school_id' => $this->schoolId,
        ];
    }

    /**
     * Obtenir les données pour la création
     */
    public function toCreateArray(): array
    {
        return [
            'name' => $this->name,
            'is_old' => $this->isOld,
            'school_id' => $this->schoolId,
        ];
    }

    /**
     * Obtenir les données pour la mise à jour
     */
    public function toUpdateArray(): array
    {
        return [
            'name' => $this->name,
            'is_old' => $this->isOld,
        ];
    }
}
