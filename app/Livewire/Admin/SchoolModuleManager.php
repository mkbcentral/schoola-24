<?php

namespace App\Livewire\Admin;

use App\Models\Module;
use App\Models\School;
use App\Services\ModuleService;
use Livewire\Component;

class SchoolModuleManager extends Component
{
    public ?School $school = null;
    public $schoolId;
    public $selectedModuleIds = [];
    public $availableModules;
    public $totalCost = 0;

    protected ModuleService $moduleService;

    public function boot(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function mount(School $school)
    {
        $this->school = $school->load('modules');
        $this->schoolId = $school->id;

        // Charger les modules disponibles
        $this->availableModules = $this->moduleService->getAvailableModules();

        // Pré-sélectionner les modules déjà affectés
        $this->selectedModuleIds = $this->moduleService->getSchoolModuleIds($this->school);

        $this->calculateTotalCost();
    }

    public function updatedSelectedModuleIds()
    {
        $this->calculateTotalCost();
    }

    public function calculateTotalCost()
    {
        $this->totalCost = Module::whereIn('id', $this->selectedModuleIds)
            ->sum('price');
    }

    public function save()
    {
        try {
            $this->moduleService->syncSchoolModules($this->school, $this->selectedModuleIds);

            session()->flash('success', 'Modules affectés avec succès à l\'école ' . $this->school->name);

            // Recharger les données
            $this->school = School::with('modules')->findOrFail($this->schoolId);

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.school-module-manager');
    }
}
