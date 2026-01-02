<?php

namespace App\Livewire\School;

use App\Services\ModuleService;
use Livewire\Component;

class MyModules extends Component
{
    public $modules;
    public $totalCost = 0;

    protected ModuleService $moduleService;

    public function boot(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function mount()
    {
        $school = auth()->user()->school;

        if ($school) {
            $this->modules = $this->moduleService->getSchoolModules($school);
            $this->totalCost = $this->moduleService->getSchoolModulesTotalCost($school);
        } else {
            $this->modules = collect();
            $this->totalCost = 0;
        }
    }

    public function render()
    {
        return view('livewire.school.my-modules');
    }
}
