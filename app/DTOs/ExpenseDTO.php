<?php

namespace App\DTOs;

class ExpenseDTO
{
    public function __construct(
        public ?int $id = null,
        public string $description = '',
        public string $month = '',
        public float $amount = 0,
        public string $currency = 'USD',
        public int $categoryExpenseId = 0,
        public int $categoryFeeId = 0,
        public ?int $schoolYearId = null,
        public ?string $createdAt = null,
    ) {}

    /**
     * Créer un DTO depuis un modèle ExpenseFee
     */
    public static function fromModel($model): self
    {
        return new self(
            id: $model->id,
            description: $model->description,
            month: $model->month,
            amount: $model->amount,
            currency: $model->currency,
            categoryExpenseId: $model->category_expense_id,
            categoryFeeId: $model->category_fee_id,
            schoolYearId: $model->school_year_id,
            createdAt: $model->created_at?->format('Y-m-d'),
        );
    }

    /**
     * Créer un DTO depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            description: $data['description'] ?? '',
            month: $data['month'] ?? date('m'),
            amount: $data['amount'] ?? 0,
            currency: $data['currency'] ?? 'USD',
            categoryExpenseId: $data['category_expense_id'] ?? $data['categoryExpenseId'] ?? 0,
            categoryFeeId: $data['category_fee_id'] ?? $data['categoryFeeId'] ?? 0,
            schoolYearId: $data['school_year_id'] ?? $data['schoolYearId'] ?? null,
            createdAt: $data['created_at'] ?? $data['createdAt'] ?? null,
        );
    }

    /**
     * Convertir en tableau pour la persistance
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'description' => $this->description,
            'month' => $this->month,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'category_expense_id' => $this->categoryExpenseId,
            'category_fee_id' => $this->categoryFeeId,
            'school_year_id' => $this->schoolYearId,
            'created_at' => $this->createdAt,
        ], fn($value) => $value !== null);
    }

    /**
     * Valider les données
     */
    public function validate(): array
    {
        $errors = [];

        if (empty($this->description)) {
            $errors['description'] = 'La description est obligatoire';
        }

        if ($this->amount <= 0) {
            $errors['amount'] = 'Le montant doit être supérieur à 0';
        }

        if (!in_array($this->currency, ['USD', 'CDF'])) {
            $errors['currency'] = 'Devise invalide';
        }

        if ($this->categoryExpenseId <= 0) {
            $errors['category_expense_id'] = 'La catégorie de dépense est obligatoire';
        }

        if ($this->categoryFeeId <= 0) {
            $errors['category_fee_id'] = 'Le type de frais est obligatoire';
        }

        return $errors;
    }

    /**
     * Vérifier si les données sont valides
     */
    public function isValid(): bool
    {
        return empty($this->validate());
    }
}
