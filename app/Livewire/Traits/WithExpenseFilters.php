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
    public int $perPage = 15;

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
        $this->perPage = 15;
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
            'date' => $this->date,
            'month' => $this->filterMonth,
            'categoryExpenseId' => $this->filterCategoryExpense,
            'currency' => $this->filterCurrency,
            'period' => $this->filterPeriod,
            'startDate' => $this->dateDebut,
            'endDate' => $this->dateFin,
            'perPage' => $this->perPage,
        ];

        if ($type === 'fee') {
            $filters['categoryFeeId'] = $this->filterCategoryFee;
        } else {
            $filters['otherSourceExpenseId'] = $this->filterOtherSource;
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
