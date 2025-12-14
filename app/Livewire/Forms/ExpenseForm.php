<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ExpenseForm extends Form
{
    public ?int $id = null; // ID de la dépense (pour édition)

    #[Validate('required', message: 'Description obligatoire')]
    #[Validate('string')]
    #[Validate('min:3', message: 'La description doit contenir au moins 3 caractères')]
    public string $description = '';

    #[Validate('required', message: 'Mois obligatoire')]
    public string $month = '';

    #[Validate('required', message: 'Montant obligatoire')]
    #[Validate('numeric', message: 'Format numérique invalide')]
    #[Validate('min:0.01', message: 'Le montant doit être supérieur à 0')]
    public float $amount = 0;

    #[Validate('required', message: 'Devise obligatoire')]
    #[Validate('in:USD,CDF', message: 'Devise invalide')]
    public string $currency = 'USD';

    #[Validate('required', message: 'Catégorie de dépense obligatoire')]
    #[Validate('integer', message: 'Format invalide')]
    #[Validate('exists:category_expenses,id', message: 'Catégorie introuvable')]
    public int $categoryExpenseId = 0;

    #[Validate('nullable')]
    #[Validate('integer', message: 'Format invalide')]
    #[Validate('exists:category_fees,id', message: 'Type de frais introuvable')]
    public ?int $categoryFeeId = null;

    #[Validate('nullable')]
    #[Validate('integer', message: 'Format invalide')]
    #[Validate('exists:other_source_expenses,id', message: 'Source de dépense introuvable')]
    public ?int $otherSourceExpenseId = null;

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        parent::reset(...$properties);
        $this->id = null;
        $this->description = '';
        $this->month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
        $this->amount = 0;
        $this->currency = 'USD';
        $this->categoryExpenseId = 0;
        $this->categoryFeeId = null;
        $this->otherSourceExpenseId = null;
    }

    /**
     * Convertir en tableau pour DTO
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'month' => $this->month,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'categoryExpenseId' => $this->categoryExpenseId,
            'categoryFeeId' => $this->categoryFeeId,
            'otherSourceExpenseId' => $this->otherSourceExpenseId,
        ];
    }

    /**
     * Charger les données depuis un DTO
     */
    public function loadFromDTO($dto): void
    {
        $this->id = $dto->id ?? null;
        $this->description = $dto->description;
        $this->month = $dto->month;
        $this->amount = $dto->amount;
        $this->currency = $dto->currency;
        $this->categoryExpenseId = $dto->categoryExpenseId;
        $this->categoryFeeId = $dto->categoryFeeId ?? null;
        $this->otherSourceExpenseId = $dto->otherSourceExpenseId ?? null;
    }
}
