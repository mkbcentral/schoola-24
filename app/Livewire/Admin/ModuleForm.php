<?php

namespace App\Livewire\Admin;

use App\Models\Module;
use App\Services\ModuleService;
use Livewire\Component;

class ModuleForm extends Component
{
    public ?Module $module = null;
    public $moduleId;
    public $name = '';
    public $code = '';
    public $price = 0;
    public $description = '';
    public $icon = 'bi bi-puzzle-fill';
    public $is_active = true;
    public $sort_order = 0;

    public $features = [];
    public $isEditing = false;

    protected ModuleService $moduleService;

    public function boot(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function mount(?Module $module = null)
    {
        if ($module && $module->exists) {
            $this->module = $module->load('features');
            $this->moduleId = $module->id;
            $this->name = $module->name;
            $this->code = $module->code;
            $this->price = $module->price;
            $this->description = $module->description;
            $this->icon = $module->icon;
            $this->is_active = $module->is_active;
            $this->sort_order = $module->sort_order;

            $this->features = $module->features->map(function ($feature) {
                return [
                    'name' => $feature->name,
                    'url' => $feature->url,
                    'icon' => $feature->icon,
                ];
            })->toArray();

            $this->isEditing = true;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:modules,code,' . $this->moduleId,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'features.*.name' => 'required|string|max:255',
            'features.*.url' => 'required|string|max:255',
            'features.*.icon' => 'nullable|string|max:100',
        ];
    }

    public function addFeature()
    {
        $this->features[] = [
            'name' => '',
            'url' => '',
            'icon' => 'bi bi-circle',
        ];
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'code' => $this->code,
                'price' => $this->price,
                'description' => $this->description,
                'icon' => $this->icon,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ];

            if ($this->isEditing) {
                $module = Module::findOrFail($this->moduleId);
                $this->moduleService->updateModule($module, $data, $this->features);
                session()->flash('success', 'Module mis à jour avec succès.');
            } else {
                $this->moduleService->createModule($data, $this->features);
                session()->flash('success', 'Module créé avec succès.');
            }

            return $this->redirect(route('admin.modules.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return $this->redirect(route('admin.modules.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.module-form');
    }
}
