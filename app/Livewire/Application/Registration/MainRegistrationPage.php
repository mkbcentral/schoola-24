<?php

namespace App\Livewire\Application\Registration;

use Livewire\Component;

class MainRegistrationPage extends Component
{
    public $caseType = 'new';
    public function openNewResponsibleStudent()
    {
        $this->dispatch('dataFormReset');
    }
    public function render()
    {
        return view('livewire.application.registration.main-registration-page');
    }
}
