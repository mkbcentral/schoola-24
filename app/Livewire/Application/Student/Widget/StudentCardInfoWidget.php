<?php

namespace App\Livewire\Application\Student\Widget;

use App\Models\Registration;
use App\Models\Student;
use Livewire\Component;

class StudentCardInfoWidget extends Component
{
    public ?Registration $registration;

    public function mount(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function edit(Student $student)
    {
        $this->dispatch('studentData', $student);
    }

    public function render()
    {
        return view('livewire.application.student.widget.student-card-info-widget');
    }
}
