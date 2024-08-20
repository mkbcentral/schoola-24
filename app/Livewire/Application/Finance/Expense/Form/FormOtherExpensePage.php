<?php

namespace App\Livewire\Application\Finance\Expense\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\OtherExpenseForm;
use App\Models\OtherExpense;
use Exception;
use Livewire\Component;

class FormOtherExpensePage extends Component
{
    protected $listeners = [
        'otherExpenseData' => 'getOtherExpense'
    ];
    public ?OtherExpense $otherExpense = null;
    public OtherExpenseForm $form;

    public function getOtherExpense(OtherExpense $otherExpense): void
    {
        $this->otherExpense = $otherExpense;
        $this->form->fill($otherExpense->toArray());
        $this->form->created_at = $otherExpense->created_at->format('Y-m-d');
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
            $this->form->update($this->otherExpense);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->otherExpense == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->otherExpense = null;
        $this->dispatch('otherExpenseListRefred');
        $this->form->created_at = date('Y-m-d');
    }

    public function cancelUpdate(): void
    {
        $this->otherExpense = null;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }

    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
    }
    public function render()
    {
        return view('livewire.application.finance.expense.form.form-other-expense-page');
    }
}
