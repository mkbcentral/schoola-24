<?php

namespace App\Livewire\Traits;

trait WithExpenseFilters
{
    // Filtres pour dépenses
    public ?string $date = null;
    public ?string $filterPeriod = null;
    public ?string $dateRange = null;
    public ?string $dateDebut = null;
    public ?string $dateFin = null;
    public ?string $filterMonth = null;
    public ?int $filterCategoryExpense = null;
    public ?int $filterCategoryFee = null;
    public ?int $filterOtherSource = null;
    public ?string $filterCurrency = null;
    public ?bool $filterIsValidated = null;

    /**
     * Initialiser les filtres
     */
    protected function initializeFilters(): void
    {
        $this->date = null;
        $this->filterPeriod = null;
        $this->dateRange = null;
        $this->dateDebut = null;
        $this->dateFin = null;
        $this->filterMonth = null;
        $this->filterCategoryExpense = null;
        $this->filterCategoryFee = null;
        $this->filterOtherSource = null;
        $this->filterCurrency = null;
        $this->filterIsValidated = null;
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters(): void
    {
        $this->initializeFilters();
        $this->resetPage();
    }

    /**
     * Obtenir le tableau de filtres
     */
    protected function getFilterArray(string $type): array
    {
        $filters = [
            'month' => $this->filterMonth,
            'categoryExpense' => $this->filterCategoryExpense,
            'currency' => $this->filterCurrency,
            'isValidated' => $this->filterIsValidated,
        ];

        if ($type === 'fee') {
            $filters['categoryFee'] = $this->filterCategoryFee;
        } else {
            $filters['otherSource'] = $this->filterOtherSource;
        }

        return $filters;
    }

    /**
     * Appliquer les filtres (déclenche le re-render)
     */
    public function applyFilters(): void
    {
        $this->resetPage();
    }
}
