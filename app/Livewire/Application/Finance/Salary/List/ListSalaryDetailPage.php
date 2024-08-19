<?php

namespace App\Livewire\Application\Finance\Salary\List;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SalaryDetailForm;
use App\Models\Salary;
use App\Models\SalaryDetail;
use Exception;
use Livewire\Component;

class ListSalaryDetailPage extends Component
{
    protected $listeners = [
        'salaryData' => 'getSalary'
    ];
    public ?Salary $salary = null;
    public SalaryDetailForm $form;
    public ?SalaryDetail $salaryDetailToEdit = null;

    public function getSalary(Salary $salary): void
    {
        $this->salary = $salary;
    }

    public function edit(SalaryDetail $salaryDetail)
    {
        $this->salaryDetailToEdit = $salaryDetail;
        $this->form->fill($salaryDetail->toArray());
    }

    public function save(): void
    {

        try {
            $this->form->create($this->salary);
            $this->dispatch('added', ['message', AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message', $ex->getMessage()]);
        }
    }
    public function update(): void
    {
        try {
            $this->form->update($this->salaryDetailToEdit);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function handlerSubmit(): void
    {
        $this->validate();
        if ($this->salaryDetailToEdit == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->form->reset();
        $this->salaryDetailToEdit = null;
    }

    public function cancelUpdate(): void
    {
        $this->salaryDetailToEdit = null;
        $this->form->reset();
    }
    public function render()
    {
        return view('livewire.application.finance.salary.list.list-salary-detail-page');
    }
}
