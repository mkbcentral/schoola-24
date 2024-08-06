<?php

namespace App\Livewire\Application\Student\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\ResponsibleStudentForm;
use App\Models\ResponsibleStudent;
use Exception;
use Livewire\Component;

class FormResponsibleStudentPage extends Component
{
    public ResponsibleStudentForm $form;
    protected $listeners = [
        "responsibleStudentData" => "getResponsibleStudent",
        "dataFormResed" => "resetFormData",
    ];
    public ?ResponsibleStudent $responsibleStudent = null;
    public function getResponsibleStudent(?ResponsibleStudent $responsibleStudent)
    {
        $this->responsibleStudent = $responsibleStudent;
        $this->form->fill($responsibleStudent->toArray());
    }
    public function resetFormData()
    {
        $this->responsibleStudent = null;
        $this->form->fill([]);
    }

    public function save()
    {
        $input = $this->validate();
        $this->form->create($input);
        $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        $this->dispatch('close-form-responsible-student');
        try {
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }
    public function update()
    {
        $input = $this->validate();
        try {
            $this->form->update($this->responsibleStudent, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('close-form-responsible-student');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }



    public function handlerSubmit()
    {
        if ($this->responsibleStudent == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('refreshListResponsibleStudent');
    }

    public function render()
    {
        return view('livewire.application.student.form.form-responsible-student-page');
    }
}
