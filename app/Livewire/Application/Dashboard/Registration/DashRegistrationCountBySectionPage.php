<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Domain\Features\Configuration\SchoolDataFeature;
use Livewire\Component;

class DashRegistrationCountBySectionPage extends Component
{
    public function render()
    {
        return view('livewire.application.dashboard.registration.dash-registration-count-by-section-page', [
            'sections' => SchoolDataFeature::getSectionList()
        ]);
    }
}
