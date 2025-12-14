<?php

namespace App\Livewire\Application\Student;

use App\Models\Registration;
use Livewire\Component;

class DetailStudentPage extends Component
{
    public ?Registration $registration;

    public function render()
    {
        return view('livewire.application.student.detail-student-page');
    }
}
