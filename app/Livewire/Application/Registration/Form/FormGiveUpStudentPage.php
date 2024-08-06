<?php

namespace App\Livewire\Application\Registration\Form;

use App\Domain\Utils\AppMessage;
use App\Models\GiveUpStudent;
use App\Models\Registration;
use Exception;
use Livewire\Component;

class FormGiveUpStudentPage extends Component
{
    protected $listeners = [
        "registrationData" => "getRegistration",
    ];

    public Registration $registration;
    public string $month;


    public function getRegistration(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function makeGiveUpStudent()
    {
        $this->validate([
            'month' => 'required|min:2'
        ]);
        try {
            GiveUpStudent::create([
                'registration_id' => $this->registration->id,
                'month' => $this->month
            ]);
            $this->registration->abandoned = true;
            $this->registration->update();
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('refreshListStudent');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.application.registration.form.form-give-up-student-page');
    }
}
