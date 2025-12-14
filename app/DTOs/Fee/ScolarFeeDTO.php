<?php

namespace App\DTOs\Fee;

class ScolarFeeDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public float $amount = 0,
        public ?int $categoryFeeId = null,
        public ?int $classRoomId = null,
        public bool $isChanged = false,
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
            amount: $data['amount'] ?? 0,
            categoryFeeId: $data['category_fee_id'] ?? null,
            classRoomId: $data['class_room_id'] ?? null,
            isChanged: $data['is_changed'] ?? false,
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
            amount: $model->amount,
            categoryFeeId: $model->category_fee_id,
            classRoomId: $model->class_room_id,
            isChanged: $model->is_changed,
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
            'amount' => $this->amount,
            'category_fee_id' => $this->categoryFeeId,
            'class_room_id' => $this->classRoomId,
            'is_changed' => $this->isChanged,
        ];
    }

    /**
     * Obtenir les données pour la création
     */
    public function toCreateArray(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'category_fee_id' => $this->categoryFeeId,
            'class_room_id' => $this->classRoomId,
            'is_changed' => $this->isChanged,
        ];
    }

    /**
     * Obtenir les données pour la mise à jour
     */
    public function toUpdateArray(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
            'is_changed' => $this->isChanged,
        ];
    }
}
