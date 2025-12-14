<?php

namespace App\Livewire\Application\Widgets;

use App\Models\School;
use App\Models\SchoolYear;
use Auth;
use Livewire\Component;

class SchoolYearLabel extends Component
{
    // Listner for refresh event
    protected $listeners = ['refreshSchoolYearLabel' => '$refresh'];

    public function render()
    {
        $workOnYear = Auth::user()->work_on_year;
        $schoolYear = SchoolYear::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->when(
                $workOnYear,
                fn ($query, $workOnYear) => $query->where('id', $workOnYear),
                fn ($query) => $query->where('is_active', true)
            )
            ->first();

        return view('livewire.application.widgets.school-year-label', [
            'schoolYear' => $schoolYear,
        ]);
    }
}
