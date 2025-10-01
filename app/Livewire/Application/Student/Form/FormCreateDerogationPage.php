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
    public $is_month = false;
    public $month_date = '';

    protected $rules = [
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'is_month' => 'boolean',
        'month_date' => 'nullable|date',
    ];

    protected $listeners = [
        'openDerogationModal' => 'setRegistration',
    ];

    public function setRegistration(Registration $registration)
    {
        $this->registration = $registration;
        $this->start_date = '';
        $this->end_date = '';
        $this->is_month = false;
        $this->month_date = '';
    }

    public function updatedIsMonth($value)
    {
        if (!$value) {
            $this->month_date = '';
        } else {
            $this->month_date = date('Y-m-d');
        }
    }

    public function save()
    {
        $this->validate();
        RegistrationDerogation::create([
            'registration_id' => $this->registration->id,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'is_monthly' => $this->is_month,
            'month_date' => $this->is_month ? $this->month_date : null,
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
