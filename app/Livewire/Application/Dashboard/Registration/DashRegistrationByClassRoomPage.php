<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\Option;
use Livewire\Component;

class DashRegistrationByClassRoomPage extends Component
{
    protected $listeners = [
        "monthFilder" => "getMonthFilter",
    ];
    public $option_filer;
    public string $month_filter = '';

    public function getMonthFilter(string $month)
    {
        $this->month_filter = $month;
    }

    public function mount()
    {
        $this->option_filer = Option::first()?->id;
    }

    public function render()
    {
        return view('livewire.application.dashboard.registration.dash-registration-by-class-room-page', [
            'classRooms' => SchoolDataFeature::getClassRoomList(
                $this->option_filer,
                null,
                null
            ),
            'counter' => RegistrationFeature::getCount(
                null,
                null,
                null,
                $this->option_filer,
                null,
                null,
                null
            )
        ]);
    }
}
