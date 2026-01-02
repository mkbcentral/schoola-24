<?php

namespace App\Livewire\Admin;

use App\Models\Module;
use App\Services\ModuleService;
use Livewire\Component;
use Livewire\WithPagination;

class ModuleManagement extends Component
{
    use WithPagination;

    public $search = '';

    protected ModuleService $moduleService;

    public function boot(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        return $this->redirect(route('admin.modules.create'), navigate: true);
    }

    public function edit($moduleId)
    {
        return $this->redirect(route('admin.modules.edit', $moduleId), navigate: true);
    }

    public function delete($moduleId)
    {
        try {
            $module = Module::findOrFail($moduleId);
            $this->moduleService->deleteModule($module);
            session()->flash('success', 'Module supprimé avec succès.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function toggleActive($moduleId)
    {
        try {
            $module = Module::findOrFail($moduleId);
            $module->update(['is_active' => !$module->is_active]);
            session()->flash('success', 'Statut mis à jour.');
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function render()
    {
        $modules = Module::with('features')
            ->withCount('schools')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.module-management', [
            'modules' => $modules,
        ]);
    }
}
