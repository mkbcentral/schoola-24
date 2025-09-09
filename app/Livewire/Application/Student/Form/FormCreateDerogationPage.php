<?php

namespace App\Livewire\Application\Student\Form;

use App\Models\Registration;
use App\Models\RegistrationDerogation;
use Livewire\Component;
use Carbon\Carbon;

class FormCreateDerogationPage extends Component
{
    public ?Registration $registration = null;
    public $start_date = '';
    public $end_date = '';

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $listeners = [
        'openDerogationModal' => 'setRegistration',
    ];

    public function setRegistration(Registration $registration)
    {
        $this->registration = $registration;
        $this->start_date = '';
        $this->end_date = '';
    }

    public function save()
    {
        $this->validate();

        RegistrationDerogation::create([
            'registration_id' => $this->registration->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        // Mettre à jour la registration
        $this->registration->is_under_derogation = true;
        $this->registration->save();

        $this->dispatch('added', ['message' => 'Dérogation créée avec succès.']);
    }

    public function render()
    {
        return view('livewire.application.student.form.form-create-derogation-page');
    }
}
