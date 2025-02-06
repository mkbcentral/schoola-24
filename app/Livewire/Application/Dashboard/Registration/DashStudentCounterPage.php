<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Models\Registration;
use Livewire\Component;

class DashStudentCounterPage extends Component
{
    public function mount() {}
    public function render()
    {
        return view('livewire.application.dashboard.registration.dash-student-counter-page', [
            'sections' => Registration::countStudentBySection(),
            'options' => Registration::countStudentByOption()
        ]);
    }
}
