<?php

namespace App\Livewire\Application\Finance\Expense\List;

use App\Models\CategoryExpense;
use App\Models\School;
use Exception;
use Livewire\Component;

class ListCategoryExpensePage extends Component
{
    protected $listeners = [
        'categoryExpenseListRefred' => '$refresh'
    ];
    public function edit(?CategoryExpense $categoryExpense)
    {
        $this->dispatch('categoryExpenseData', $categoryExpense);
    }

    public function delete(?CategoryExpense $categoryExpense)
    {
        try {
            $categoryExpense->delete();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.finance.expense.list.list-category-expense-page', [
            'categoryExpenses' => CategoryExpense::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->get()
        ]);
    }
}
