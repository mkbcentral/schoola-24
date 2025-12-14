<?php

namespace App\DTOs\Configuration;

class RateDTO
{
    public function __construct(
        public ?int $id = null,
        public float $amount = 0,
        public ?int $schoolId = null,
        public bool $isChanged = false,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    /**
     * Créer un DTO depuis un modèle Rate
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            amount: $model->amount,
            schoolId: $model->school_id,
            isChanged: $model->is_changed,
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
            amount: $data['amount'] ?? 0,
            schoolId: $data['school_id'] ?? null,
            isChanged: $data['is_changed'] ?? false,
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
            'amount' => $this->amount,
            'school_id' => $this->schoolId,
            'is_changed' => $this->isChanged,
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

        if ($this->amount <= 0) {
            $errors['amount'] = 'Le montant doit être supérieur à 0';
        }

        if ($this->schoolId === null) {
            $errors['school_id'] = 'L\'école est requise';
        }

        return $errors;
    }
}
