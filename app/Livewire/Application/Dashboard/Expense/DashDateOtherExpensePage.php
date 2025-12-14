<?php

namespace App\Livewire\Application\Dashboard\Expense;

use App\Models\OtherExpense;
use Livewire\Component;

class DashDateOtherExpensePage extends Component
{
    public ?string $date_filter = '';

    public int $otherSourceExpenseIdFilter = 0;

    public int $categoryExpenseIdFilter = 0;

    public function mount(): void
    {
        $this->date_filter = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.application.dashboard.expense.dash-date-other-expense-page', [
            'expense' => OtherExpense::getTotalExpensesByDate(
                $this->categoryExpenseIdFilter,
                $this->otherSourceExpenseIdFilter,
                $this->date_filter
            )->first(),
        ]);
    }
}
