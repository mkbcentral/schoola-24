<?php

namespace App\Livewire\Application\Dashboard\Expense;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Enums\RoleType;
use App\Models\CategoryFee;
use App\Models\ExpenseFee;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashDateCostExpensePage extends Component
{
    public int $category_fee_filter = 0, $category_expense_id_filter = 0;
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
