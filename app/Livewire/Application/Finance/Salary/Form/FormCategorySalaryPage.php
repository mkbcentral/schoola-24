<?php

namespace App\Livewire\Application\Finance\Salary\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\CategorySalaryForm;
use App\Models\CategorySalary;
use Exception;
use Livewire\Component;

class FormCategorySalaryPage extends Component
{
    protected $listeners = ["categorySalaryData" => "getCategorySalary"];
    public ?CategorySalary $categorySalarySelected = null;
    public CategorySalaryForm $form;

    public function getCategorySalary(CategorySalary $categorySalary)
    {
        $this->categorySalarySelected = $categorySalary;
        $this->form->fill($categorySalary->toArray());
    }

    public function save()
    {
        $input = $this->validate();
        try {
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $input = $this->validate();
        try {
            $this->form->update($this->categorySalarySelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->categorySalarySelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('catSalaryataRefreshed');
        $this->form->reset();
    }

    public function cancelUpdate()
    {
        $this->categorySalarySelected = null;
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.application.finance.salary.form.form-category-salary-page');
    }
}
