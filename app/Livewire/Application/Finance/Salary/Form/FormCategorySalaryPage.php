<?php

namespace App\Livewire\Application\Finance\Salary\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\CategorySalaryForm;
use App\Models\CategorySalary;
use Exception;
use Livewire\Component;

class FormCategorySalaryPage extends Component
{
    protected $listeners = [
        'categorySalaryData' => 'getCategorySalary',
        'initialCatSalaryForm' => 'initSalaryForm',
    ];

    public ?CategorySalary $categorySalarySelected = null;

    public CategorySalaryForm $form;

    public function initSalaryForm(): void
    {
        $this->categorySalarySelected = null;
        $this->form->reset();
    }

    public function getCategorySalary(CategorySalary $categorySalary): void
    {
        $this->categorySalarySelected = $categorySalary;
        $this->form->fill($categorySalary->toArray());
    }

    public function save(): void
    {
        $input = $this->validate();
        try {
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update(): void
    {
        $input = $this->validate();
        try {
            $this->form->update($this->categorySalarySelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit(): void
    {
        if ($this->categorySalarySelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('catSalaryDataRefreshed');
        $this->form->reset();
    }

    public function cancelUpdate(): void
    {
        $this->categorySalarySelected = null;
        $this->form->reset();
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.salary.form.form-category-salary-page');
    }
}
