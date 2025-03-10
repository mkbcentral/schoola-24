<?php

namespace App\Livewire\Application\Finance\Expense\List;

use App\Domain\Features\Finance\OtherExpenseFeature;
use App\Domain\Utils\AppMessage;
use App\Models\OtherExpense;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListOtherExpensePage extends Component
{
    use WithPagination;
    protected $listeners = [
        'otherExpenseListRefreshed' => '$refresh'
    ];
    public ?string $date_filter = null;
    public ?string    $month_filter = '';
    public ?string    $currency_filter = '';
    public int  $other_source_expense_id_filter = 0;
    public int $category_expense_id_filter = 0;
    public int $per_page = 25;

    public  function newOtherExpenseFee():void
    {
        $this->dispatch('initialOtherExpenseForm');
    }
    public function updatedMonthFilter(): void
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter(): void
    {
        $this->month_filter = "";
    }
    public function edit(?OtherExpense $otherExpense): void
    {
        $this->dispatch('otherExpenseData', $otherExpense);
    }

    public function delete(?OtherExpense $otherExpense): void
    {
        try {
            $otherExpense->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function  mount(): void
    {
        $this->month_filter=date('m');
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.expense.list.list-other-expense-page', [
            'otherExpenses' => OtherExpenseFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->other_source_expense_id_filter,
                $this->category_expense_id_filter,
                $this->currency_filter,
                $this->per_page
            ),
            'total_usd' => OtherExpenseFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                $this->other_source_expense_id_filter,
                $this->category_expense_id_filter,
                'USD',
            ),
            'total_cdf' => OtherExpenseFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                $this->other_source_expense_id_filter,
                $this->category_expense_id_filter,
                'CDF',
            )
        ]);
    }
}
