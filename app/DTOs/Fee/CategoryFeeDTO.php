<?php

namespace App\DTOs\Fee;

class CategoryFeeDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public ?int $schoolYearId = null,
        public ?int $schoolId = null,
        public bool $isStateFee = false,
        public string $currency = 'USD',
        public bool $isPaidInInstallment = false,
        public bool $isPaidForRegistration = false,
        public bool $isForDash = false,
        public bool $isAccessory = false,
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
            schoolYearId: $data['school_year_id'] ?? null,
            schoolId: $data['school_id'] ?? null,
            isStateFee: $data['is_state_fee'] ?? false,
            currency: $data['currency'] ?? 'USD',
            isPaidInInstallment: $data['is_paid_in_installment'] ?? false,
            isPaidForRegistration: $data['is_paid_for_registration'] ?? false,
            isForDash: $data['is_for_dash'] ?? false,
            isAccessory: $data['is_accessory'] ?? false,
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
            schoolYearId: $model->school_year_id,
            schoolId: $model->school_id,
            isStateFee: $model->is_state_fee,
            currency: $model->currency,
            isPaidInInstallment: $model->is_paid_in_installment,
            isPaidForRegistration: $model->is_paid_for_registration,
            isForDash: $model->is_for_dash,
            isAccessory: $model->is_accessory,
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
            'school_year_id' => $this->schoolYearId,
            'school_id' => $this->schoolId,
            'is_state_fee' => $this->isStateFee,
            'currency' => $this->currency,
            'is_paid_in_installment' => $this->isPaidInInstallment,
            'is_paid_for_registration' => $this->isPaidForRegistration,
            'is_for_dash' => $this->isForDash,
            'is_accessory' => $this->isAccessory,
        ];
    }

    /**
     * Obtenir les données pour la création
     */
    public function toCreateArray(): array
    {
        return [
            'name' => $this->name,
            'school_year_id' => $this->schoolYearId,
            'school_id' => $this->schoolId,
            'is_state_fee' => $this->isStateFee,
            'currency' => $this->currency,
            'is_paid_in_installment' => $this->isPaidInInstallment,
            'is_paid_for_registration' => $this->isPaidForRegistration,
            'is_for_dash' => $this->isForDash,
            'is_accessory' => $this->isAccessory,
        ];
    }

    /**
     * Obtenir les données pour la mise à jour
     */
    public function toUpdateArray(): array
    {
        return [
            'name' => $this->name,
            'is_state_fee' => $this->isStateFee,
            'currency' => $this->currency,
            'is_paid_in_installment' => $this->isPaidInInstallment,
            'is_paid_for_registration' => $this->isPaidForRegistration,
            'is_for_dash' => $this->isForDash,
            'is_accessory' => $this->isAccessory,
        ];
    }
}
