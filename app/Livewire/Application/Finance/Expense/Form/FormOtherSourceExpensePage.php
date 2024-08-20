<?php

namespace App\Livewire\Application\Finance\Expense\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\OtherSourceExpenseForm;
use App\Models\OtherSourceExpense;
use Exception;
use Livewire\Component;

class FormOtherSourceExpensePage extends Component
{
    protected $listeners = [
        'categoryExpenseData' => 'getOtherSourceExpense'
    ];
    public ?OtherSourceExpense $otherSourceExpense = null;
    public OtherSourceExpenseForm $form;

    public function getOtherSourceExpense(OtherSourceExpense $otherSourceExpense): void
    {
        $this->otherSourceExpense = $otherSourceExpense;
        $this->form->fill($otherSourceExpense->toArray());
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
            $this->form->update($this->otherSourceExpense);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->otherSourceExpense == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->otherSourceExpense = null;
        $this->dispatch('categoryExpenseListRefred');
    }

    public function cancelUpdate(): void
    {
        $this->otherSourceExpense = null;
        $this->form->reset();
    }


    public function render()
    {
        return view('livewire.application.finance.expense.form.form-other-source-expense-page');
    }
}
