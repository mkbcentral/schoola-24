<?php

namespace App\Livewire\Application\Widgets;

use App\Models\SchoolYear;
use Auth;
use Livewire\Component;

class SchoolYearLabel extends Component
{
    //Listner for refresh event
    protected $listeners = ['refreshSchoolYearLabel' => '$refresh'];
    public function render()
    {
        return view('livewire.application.widgets.school-year-label', [
            'schoolYear' => SchoolYear::DEFAULT_SCHOOL_YEAR_NAME()
        ]);
    }
}
