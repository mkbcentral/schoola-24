<?php

namespace App\Livewire\Traits;

trait WithExpenseFilters
{
    // Filters
    public string $searchTerm = '';
    public ?string $date = null;
    public ?string $filterMonth = null;
    public string $filterCurrency = '';
    public int $filterCategoryExpense = 0;
    public int $filterCategoryFee = 0;
    public int $filterOtherSource = 0;
    public string $filterPeriod = '';
    public ?string $dateDebut = null;
    public ?string $dateFin = null;
    public ?string $dateRange = null;

    /**
     * Initialiser les filtres
     */
    public function initializeFilters(): void
    {
        $this->filterMonth = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters(): void
    {
        $this->searchTerm = '';
        $this->date = null;
        $this->filterMonth = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
        $this->filterCurrency = '';
        $this->filterCategoryExpense = 0;
        $this->filterCategoryFee = 0;
        $this->filterOtherSource = 0;
        $this->filterPeriod = '';
        $this->dateDebut = null;
        $this->dateFin = null;
        $this->dateRange = null;
        $this->resetPage();
    }

    /**
     * Appliquer un filtre de période
     */
    public function applyPeriodFilter(string $period): void
    {
        $this->filterPeriod = $period;
        $this->resetPage();
    }

    /**
     * Obtenir le tableau de filtres pour le DTO
     */
    protected function getFilterArray(string $expenseType): array
    {
        $filters = [
            'month' => $this->filterMonth,
            'currency' => $this->filterCurrency,
            'categoryExpenseId' => $this->filterCategoryExpense,
            'period' => $this->dateRange ?: $this->filterPeriod,
            'startDate' => $this->dateDebut,
            'endDate' => $this->dateFin,
            'date' => $this->date,
        ];

        if ($expenseType === 'fee') {
            $filters['categoryFeeId'] = $this->filterCategoryFee;
        } else {
            $filters['otherSourceExpenseId'] = $this->filterOtherSource;
        }

        return $filters;
    }
}
