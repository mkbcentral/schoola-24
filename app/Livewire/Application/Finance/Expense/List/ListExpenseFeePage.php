<?php

namespace App\Livewire\Application\Finance\Expense\List;

use App\Domain\Features\Finance\ExpenseFeeFeature;
use App\Models\ExpenseFee;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListExpenseFeePage extends Component
{
    use WithPagination;
    protected $listeners = [
        'expenseFeeListRefred' => '$refresh'
    ];
    public ?string $date_filter = null,
        $month_filter = '',
        $currency_filter = '';
    public int  $category_fee_id_filter = 0, $category_expense_id_filter = 0, $per_page = 10;

    public function updatedMonthFilter()
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter()
    {
        $this->month_filter = "";
    }
    public function edit(?ExpenseFee $expenseFee)
    {
        $this->dispatch('expenseFeeData', $expenseFee);
    }

    public function delete(?ExpenseFee $expenseFee)
    {
        try {
            $expenseFee->delete();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->month_filter = date('m');
    }
    public function render()
    {
        return view('livewire.application.finance.expense.list.list-expense-fee-page', [
            'expenseFees' => ExpenseFeeFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->category_fee_id_filter,
                $this->category_expense_id_filter,
                $this->currency_filter,
                $this->per_page
            ),
            'total_usd' => ExpenseFeeFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                $this->category_fee_id_filter,
                $this->category_expense_id_filter,
                'USD',
            ),
            'total_cdf' => ExpenseFeeFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                $this->category_fee_id_filter,
                $this->category_expense_id_filter,
                'CDF',
            )
        ]);
    }
}
