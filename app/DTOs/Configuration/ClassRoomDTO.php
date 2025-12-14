<?php

namespace App\DTOs\Configuration;

class ClassRoomDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public ?int $optionId = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    /**
     * Créer un DTO depuis un modèle ClassRoom
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            optionId: $model->option_id,
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
            optionId: $data['option_id'] ?? null,
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
            'option_id' => $this->optionId,
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
            $errors['name'] = 'Le nom de la classe est requis';
        }

        if ($this->optionId === null) {
            $errors['option_id'] = 'L\'option est requise';
        }

        return $errors;
    }
}
