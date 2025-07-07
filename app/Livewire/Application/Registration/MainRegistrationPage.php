<?php

namespace App\Livewire\Application\Registration;

use Livewire\Component;

class MainRegistrationPage extends Component
{
    public $responsible_student_id;

    //upaded responsible_student_id
    public function updatedResponsibleStudentId($value)
    {
        $this->dispatch('responsibleStudentDataOnReg', $value);
    }

    public function openNewResponsibleStudent()
    {
        $this->dispatch('dataFormReset');
    }
    public function render()
    {
        return view('livewire.application.registration.main-registration-page');
    }
}
