<?php

namespace App\Livewire\Application\Finance\Expense\List;

use App\Models\OtherSourceExpense;
use App\Models\School;
use Exception;
use Livewire\Component;

class ListOtherSourceExpensePage extends Component
{
    protected $listeners = [
        'categoryExpenseListRefred' => '$refresh'
    ];
    public function edit(?OtherSourceExpense $otherSourceExpense)
    {
        $this->dispatch('categoryExpenseData', $otherSourceExpense);
    }
    public function delete(?OtherSourceExpense $otherSourceExpense)
    {
        try {
            $otherSourceExpense->delete();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function render()
    {
        return view('livewire.application.finance.expense.list.list-other-source-expense-page', [
            'otherSourceExpenses' => OtherSourceExpense::query()
                ->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->get()
        ]);
    }
}
