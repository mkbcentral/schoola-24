<?php

namespace App\Livewire\Application\Finance\Expense\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\ExpenseFeeForm;
use App\Models\ExpenseFee;
use Exception;
use Livewire\Component;

class FormExpensePage extends Component
{
    protected $listeners = [
        'expenseFeeData' => 'getExpenseFee',
        'initialExpenseForm' => 'initExpenseForm',
    ];
    public ?ExpenseFee $expenseFee = null;
    public ExpenseFeeForm $form;
    public function initExpenseForm(): void
    {
        $this->form->reset();
        $this->expenseFee = null;
        $this->form->created_at = date('Y-m-d');
        $this->form->month=date('m');
    }
    public function getExpenseFee(ExpenseFee $expenseFee): void
    {
        $this->expenseFee = $expenseFee;
        $this->form->fill($expenseFee->toArray());
        $this->form->created_at = $expenseFee->created_at->format('Y-m-d');
    }
    public function save(): void
    {
        try {
            $this->form->create();
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }
    public function update(): void
    {
        try {
            $this->form->update($this->expenseFee);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->expenseFee == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->expenseFee = null;
        $this->dispatch('expenseFeeListRefreshed');
        $this->form->created_at = date('Y-m-d');
        $this->form->month=date('m');
    }
    public function cancelUpdate(): void
    {
        $this->expenseFee = null;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }
    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
    }
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.expense.form.form-expense-page');
    }
}
