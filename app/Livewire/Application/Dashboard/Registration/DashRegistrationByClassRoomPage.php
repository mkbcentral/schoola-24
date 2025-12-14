<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\Option;
use App\Models\School;
use Livewire\Component;

class DashRegistrationByClassRoomPage extends Component
{
    protected $listeners = [
        'monthFilder' => 'getMonthFilter',
    ];

    public $option_filer;

    public string $month_filter = '';

    public function getMonthFilter(string $month)
    {
        $this->month_filter = $month;
    }

    public function mount()
    {
        $this->option_filer = Option::query()
            ->join('sections', 'sections.id', 'options.section_id')
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->select('options.*')
            ->first()->id;
    }

    public function render()
    {
        return view('livewire.application.dashboard.registration.dash-registration-by-class-room-page', [
            'classRooms' => SchoolDataFeature::getClassRoomList(
                $this->option_filer,
                null,
                null
            ),
            'counter' => RegistrationFeature::getCountAll(
                null,
                $this->month_filter,
                null,
                $this->option_filer,
                null,
                null
            ),
        ]);
    }
}
