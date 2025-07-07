<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Models\Option;
use App\Models\Registration;
use Livewire\Component;

class DashStudentCounterPage extends Component
{
    public int $selectedSection = 0;
    public ?Option $selectedOption;

    //change selected option
    public function changeOption($name)
    {
        $this->selectedOption = Option::where('name', $name)->first();
    }


    public function mount()
    {
        $this->selectedSection = 0;
        $this->selectedOption = Option::first() ?? null;
    }
    public function render()
    {
        return view('livewire.application.dashboard.registration.dash-student-counter-page', [
            'sections' => Registration::countStudentBySection(),
            'options' => Registration::countStudentByOption()
        ]);
    }
}
