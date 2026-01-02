<?php

namespace App\Services;

use App\Models\Module;
use App\Models\ModuleFeature;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class ModuleService
{
    /**
     * Affecter un module à une école
     */
    public function assignToSchool(School $school, Module $module): void
    {
        if (!$school->modules()->where('module_id', $module->id)->exists()) {
            $school->modules()->attach($module->id, [
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Retirer un module d'une école
     */
    public function removeFromSchool(School $school, Module $module): void
    {
        $school->modules()->detach($module->id);
    }

    /**
     * Synchroniser les modules d'une école (affecter/retirer en masse)
     */
    public function syncSchoolModules(School $school, array $moduleIds): void
    {
        $syncData = [];
        foreach ($moduleIds as $moduleId) {
            $syncData[$moduleId] = [
                'assigned_at' => now(),
                'assigned_by' => auth()->id(),
            ];
        }

        $school->modules()->sync($syncData);
    }

    /**
     * Obtenir tous les modules disponibles avec leurs statistiques
     */
    public function getAllModules()
    {
        return Module::with('features')
            ->withCount('schools')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtenir les modules actifs disponibles
     */
    public function getAvailableModules()
    {
        return Module::active()
            ->with('features')
            ->withCount('schools')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtenir les modules d'une école
     */
    public function getSchoolModules(School $school)
    {
        return $school->modules()
            ->with('features')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Créer un nouveau module avec ses fonctionnalités
     */
    public function createModule(array $data, array $features = []): Module
    {
        return DB::transaction(function () use ($data, $features) {
            $module = Module::create($data);

            if (!empty($features)) {
                foreach ($features as $index => $feature) {
                    ModuleFeature::create([
                        'module_id' => $module->id,
                        'name' => $feature['name'],
                        'url' => $feature['url'],
                        'icon' => $feature['icon'] ?? null,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $module->load('features');
        });
    }

    /**
     * Mettre à jour un module et ses fonctionnalités
     */
    public function updateModule(Module $module, array $data, array $features = []): Module
    {
        return DB::transaction(function () use ($module, $data, $features) {
            $module->update($data);

            if (isset($features)) {
                // Supprimer les anciennes fonctionnalités
                $module->features()->delete();

                // Créer les nouvelles
                foreach ($features as $index => $feature) {
                    ModuleFeature::create([
                        'module_id' => $module->id,
                        'name' => $feature['name'],
                        'url' => $feature['url'],
                        'icon' => $feature['icon'] ?? null,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $module->load('features');
        });
    }

    /**
     * Supprimer un module
     */
    public function deleteModule(Module $module): bool
    {
        return DB::transaction(function () use ($module) {
            // Les fonctionnalités seront supprimées automatiquement (cascade)
            // Les relations school_module seront supprimées automatiquement (cascade)
            return $module->delete();
        });
    }

    /**
     * Obtenir le coût total des modules d'une école
     */
    public function getSchoolModulesTotalCost(School $school): float
    {
        return $school->getTotalModulesCost();
    }

    /**
     * Vérifier si une école a un module spécifique
     */
    public function schoolHasModule(School $school, string $moduleCode): bool
    {
        return $school->hasModule($moduleCode);
    }

    /**
     * Obtenir les IDs des modules affectés à une école
     */
    public function getSchoolModuleIds(School $school): array
    {
        return $school->modules()->pluck('modules.id')->toArray();
    }
}
