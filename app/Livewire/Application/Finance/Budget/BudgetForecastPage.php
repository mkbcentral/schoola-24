<?php

namespace App\Livewire\Application\Finance\Budget;

use App\Domain\Helpers\DateFormatHelper;
use App\Models\ExpenseFee;
use App\Models\OtherExpense;
use App\Models\SchoolYear;
use Livewire\Component;

class BudgetForecastPage extends Component
{
    public $expensesByCategory = [];

    public $months = [];

    public function mount()
    {
        $this->months = DateFormatHelper::getFrMonths();
        $this->loadForecastData();
    }

    public function loadForecastData()
    {
        $expenseFees = ExpenseFee::with('categoryExpense')
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('category_expense_id', '!=', 13)
            ->get()
            ->map(function ($item) {
                $amount = $item->currency === 'CDF' ? $item->amount / 2850 : $item->amount;

                return [
                    'amount' => $amount,
                    'category_id' => $item->category_expense_id,
                    'category_name' => $item->categoryExpense->name ?? 'Inconnu',
                    'month' => $item->month,
                ];
            });

        $otherExpenses = OtherExpense::with('categoryExpense')
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->where('category_expense_id', '!=', 13)
            ->get()
            ->map(function ($item) {
                $amount = $item->currency === 'CDF' ? $item->amount / 2850 : $item->amount;

                return [
                    'amount' => $amount,
                    'category_id' => $item->category_expense_id,
                    'category_name' => $item->categoryExpense->name ?? 'Inconnu',
                    'month' => $item->month,
                ];
            });

        $allExpenses = $expenseFees->concat($otherExpenses);
        $months = $this->months;
        $this->expensesByCategory = $allExpenses->groupBy('category_id')->map(function ($items, $categoryId) use ($months) {
            $categoryName = $items->first()['category_name'];
            $monthlyAmounts = [];
            foreach ($months as $m) {
                $monthlyAmounts[$m['number']] = $items->where('month', $m['number'])->sum('amount');
            }

            return [
                'category_id' => $categoryId,
                'category_name' => $categoryName,
                'monthly_amounts' => $monthlyAmounts,
                'total_amount' => array_sum($monthlyAmounts),
            ];
        });
        $this->dispatch('refresh-budget-forecast-monthly', params: $this->expensesByCategory);
        $this->dispatch('refresh-budget-forecast', params: $this->expensesByCategory);
    }

    public function render()
    {
        return view('livewire.application.finance.budget.budget-forecast-page');
    }
}
