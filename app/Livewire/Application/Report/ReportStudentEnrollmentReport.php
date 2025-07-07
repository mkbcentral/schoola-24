<?php

namespace App\Livewire\Application\Report;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Helpers\DateFormatHelper;
use App\Models\SchoolYear;
use App\Models\Section;
use Livewire\Component;

class ReportStudentEnrollmentReport extends Component
{
    public $selectedSection = null;
    public $selectedOption = null;

    public function mount()
    {
        $this->selectedSection = Section::query()->first()->id;
    }

    //selectSection
    public function selectSection($sectionId)
    {
        $this->selectedSection = $sectionId;
    }

    //selectSection
    public function selectOption($optionId)
    {
        $this->selectedOption = $optionId;
    }

    public function render()
    {
        return view('livewire.application.report.report-student-enrollment-report', [
            'sections' => Section::with([
                'options.classRooms' => function ($query) {
                    $query->withCount([
                        'registrations as registration_count' => function ($q) {
                            $q->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
                        }
                    ]);
                },
                'options', // eager load options to avoid N+1
                'options.classRooms', // eager load classRooms to avoid N+1
            ])->get(),

            'months' => DateFormatHelper::getSchoolFrMonths(),

        ]);
    }
}
