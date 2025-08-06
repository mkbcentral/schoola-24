<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Models\Registration;
use App\Models\SchoolYear;
use Livewire\Component;

class StudentInfoCardPage extends Component
{
    public function mount() {}
    public function render()
    {
        return view('livewire.application.dashboard.registration.student-info-card-page', [
            'count_all' => Registration::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())->count(),
            'registrations' => Registration::selectRaw('is_old, COUNT(*) as total, CASE WHEN is_old = true THEN "ancien" ELSE "nouveau" END as status')
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->groupBy('is_old')
                ->get(),
            'genders' => Registration::countByGender()
        ]);
    }
}
