<?php

namespace App\Livewire\Application\Dashboard\Expense;

use App\Models\OtherExpense;
use Livewire\Component;

class DashOtherExpensePage extends Component
{
    public int  $otherSourceExpenseIdFilter = 0;
    public int $categoryExpenseIdFilter = 0;
    public function render()
    {
        return view('livewire.application.dashboard.expense.dash-other-expense-page', [
            'expenses' => OtherExpense::getTotalExpensesByMonth(
                $this->categoryExpenseIdFilter,
                $this->otherSourceExpenseIdFilter
            ),
        ]);
    }
}
