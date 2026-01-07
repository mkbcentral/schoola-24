<?php

namespace App\Livewire\Application\Widgets;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SchoolYearLabel extends Component
{
    public ?object $schoolYear = null;

    public function mount()
    {
        $this->loadSchoolYear();
    }

    protected function loadSchoolYear()
    {
        try {
            $this->schoolYear = DB::table('school_years')
                ->where('active', true)
                ->first();
        } catch (\Exception $e) {
            $this->schoolYear = (object) ['name' => 'N/A'];
        }
    }

    public function render()
    {
        return view('livewire.application.widgets.school-year-label');
    }
}
