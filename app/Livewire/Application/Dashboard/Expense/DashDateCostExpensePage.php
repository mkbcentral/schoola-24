<?php

namespace App\Livewire\Application\Dashboard\Expense;

use App\Models\ExpenseFee;
use Livewire\Component;

class DashDateCostExpensePage extends Component
{
    public int $category_fee_filter = 0;

    public int $category_expense_id_filter = 0;

    public string $date_filter = '';

    public function mount(): void
    {
        $this->date_filter = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.application.dashboard.expense.dash-date-cost-expense-page', [
            'expense' => ExpenseFee::getTotalExpensesByDate(
                $this->category_fee_filter,
                $this->category_expense_id_filter,
                $this->date_filter
            )->first(),
        ]);
    }
}
