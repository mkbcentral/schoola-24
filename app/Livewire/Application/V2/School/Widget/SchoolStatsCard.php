<?php

namespace App\Livewire\Application\V2\School\Widget;

use App\Services\SchoolManagementService;
use Livewire\Component;

class SchoolStatsCard extends Component
{
    public array $stats = [];

    // Service injecté via boot
    protected SchoolManagementService $schoolManagementService;

    /**
     * Injection de dépendances via boot
     */
    public function boot(SchoolManagementService $schoolManagementService): void
    {
        $this->schoolManagementService = $schoolManagementService;
    }

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = $this->schoolManagementService->getGeneralStats();
    }

    public function render()
    {
        return view('livewire.application.v2.school.widget.school-stats-card');
    }
}
