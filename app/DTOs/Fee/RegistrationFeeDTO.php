<?php

namespace App\DTOs\Fee;

class RegistrationFeeDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public float $amount = 0,
        public ?int $optionId = null,
        public ?int $categoryRegistrationFeeId = null,
        public ?int $schoolYearId = null,
        public string $currency = 'USD',
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
            optionId: $data['option_id'] ?? null,
            categoryRegistrationFeeId: $data['category_registration_fee_id'] ?? null,
            schoolYearId: $data['school_year_id'] ?? null,
            currency: $data['currency'] ?? 'USD',
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
            optionId: $model->option_id,
            categoryRegistrationFeeId: $model->category_registration_fee_id,
            schoolYearId: $model->school_year_id,
            currency: $model->currency,
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
            'option_id' => $this->optionId,
            'category_registration_fee_id' => $this->categoryRegistrationFeeId,
            'school_year_id' => $this->schoolYearId,
            'currency' => $this->currency,
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
            'option_id' => $this->optionId,
            'category_registration_fee_id' => $this->categoryRegistrationFeeId,
            'school_year_id' => $this->schoolYearId,
            'currency' => $this->currency,
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
            'currency' => $this->currency,
        ];
    }
}
