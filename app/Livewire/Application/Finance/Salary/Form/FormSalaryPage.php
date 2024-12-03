<?php

namespace App\Livewire\Application\Finance\Salary\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SalaryForm;
use App\Models\Salary;
use Exception;
use Livewire\Component;

class FormSalaryPage extends Component
{
    protected $listeners = [
        'salaryData' => 'getSalary',
        'initialSalaryForm' => 'initForm',
    ];
    public ?Salary $salary = null;
    public SalaryForm $form;

    public function initForm(): void
    {
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }

    public function getSalary(Salary $salary): void
    {
        $this->salary = $salary;
        $this->form->fill($salary->toArray());
        $this->form->created_at = $salary->created_at->format('Y-m-d');
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
            $this->form->update($this->salary);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->salary == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->salary = null;
        $this->dispatch('SalaryListReferred');
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }

    public function cancelUpdate(): void
    {
        $this->salary = null;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }

    public function mount(): void
    {
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.finance.salary.form.form-salary-page');
    }
}
