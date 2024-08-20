<?php

namespace App\Livewire\Application\Finance\Expense\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\CategoryExpenseForm;
use App\Models\CategoryExpense;
use Exception;
use Livewire\Component;

class FormCategoryExpensePage extends Component
{
    protected $listeners = [
        'categoryExpenseData' => 'getCategoryExpense'
    ];
    public ?CategoryExpense $categoryExpense = null;
    public CategoryExpenseForm $form;

    public function getCategoryExpense(CategoryExpense $categoryExpense): void
    {
        $this->categoryExpense = $categoryExpense;
        $this->form->fill($categoryExpense->toArray());
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
            $this->form->update($this->categoryExpense);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->categoryExpense == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->categoryExpense = null;
        $this->dispatch('categoryExpenseListRefred');
    }

    public function cancelUpdate(): void
    {
        $this->categoryExpense = null;
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.application.finance.expense.form.form-category-expense-page');
    }
}
