<?php

namespace App\Livewire\Application\V2\Registration\Widget;

use App\DTOs\Registration\RegistrationFilterDTO;
use App\Services\Registration\RegistrationService;
use Livewire\Component;

class RegistrationStatsCard extends Component
{
    public $sectionId = null;
    public $optionId = null;
    public $classRoomId = null;

    protected $listeners = [
        'registration-created' => '$refresh',
        'registration-updated' => '$refresh',
        'registration-deleted' => '$refresh',
        'filters-updated' => 'updateFilters',
    ];

    private RegistrationService $registrationService;

    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function updateFilters($filters)
    {
        $this->sectionId = $filters['sectionId'] ?? null;
        $this->optionId = $filters['optionId'] ?? null;
        $this->classRoomId = $filters['classRoomId'] ?? null;
    }

    public function render()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'section_id' => $this->sectionId,
            'option_id' => $this->optionId,
            'class_room_id' => $this->classRoomId,
        ]);

        $stats = $this->registrationService->getStats($filter);

        return view('livewire.application.v2.registration.widget.registration-stats-card', [
            'stats' => $stats->toArray(),
        ]);
    }
}
