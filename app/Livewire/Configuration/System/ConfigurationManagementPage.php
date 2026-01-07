<?php

namespace App\Livewire\Configuration\System;

use App\Livewire\Traits\WithFlashMessages;
use App\Models\School;
use App\Services\Configuration\RateService;
use App\Services\Configuration\SchoolYearService;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationManagementPage extends Component
{
    use WithPagination;
    use WithFlashMessages;

    // Type de configuration actif
    public string $configType = 'school-year';

    // Services injectés via boot
    protected SchoolYearService $schoolYearService;
    protected RateService $rateService;

    // Listeners
    protected $listeners = [
        'schoolYearSaved' => 'handleSchoolYearSaved',
        'rateSaved' => 'handleRateSaved',
    ];

    protected $paginationTheme = 'bootstrap';

    /**
     * Injection de dépendances via boot
     */
    public function boot(
        SchoolYearService $schoolYearService,
        RateService $rateService
    ): void {
        $this->schoolYearService = $schoolYearService;
        $this->rateService = $rateService;
    }

    /**
     * Changer le type de configuration
     */
    public function switchConfigType(string $type): void
    {
        $this->configType = $type;
        $this->resetPage();
        $this->clearMessage();
    }

    /**
     * Ouvrir le modal pour créer
     */
    public function openCreateModal(): void
    {
        $this->clearMessage();

        if ($this->configType === 'school-year') {
            $this->dispatch('openSchoolYearModal');
        } else {
            $this->dispatch('openRateModal');
        }
    }

    /**
     * Ouvrir le modal pour éditer
     */
    public function openEditModal(int $id): void
    {
        $this->clearMessage();

        if ($this->configType === 'school-year') {
            $this->dispatch('openSchoolYearEditModal', id: $id);
        } else {
            $this->dispatch('openRateEditModal', id: $id);
        }
    }

    /**
     * Gérer la sauvegarde d'une année scolaire
     */
    public function handleSchoolYearSaved(array $data): void
    {
        if ($data['type'] === 'success') {
            $this->success($data['message']);
        } else {
            $this->error($data['message']);
        }
        $this->resetPage();
    }

    /**
     * Gérer la sauvegarde d'un taux
     */
    public function handleRateSaved(array $data): void
    {
        if ($data['type'] === 'success') {
            $this->success($data['message']);
        } else {
            $this->error($data['message']);
        }
        $this->resetPage();
    }

    /**
     * Demande de confirmation pour supprimer une année scolaire
     */
    public function confirmDeleteSchoolYear(int $id): void
    {
        $schoolYear = \App\Models\SchoolYear::find($id);

        if (!$schoolYear) {
            $this->error('Année scolaire introuvable');
            return;
        }

        $this->dispatch('delete-school-year-dialog', [
            'id' => $schoolYear->id,
            'name' => $schoolYear->name,
            'isActive' => $schoolYear->is_active,
        ]);
    }

    /**
     * Supprimer une année scolaire
     */
    public function deleteSchoolYear(int $id): void
    {
        $result = $this->schoolYearService->delete($id);

        if ($result['success']) {
            $this->dispatch('item-deleted', ['message' => $result['message']]);
            $this->resetPage();
        } else {
            $this->dispatch('delete-failed', ['message' => $result['message']]);
        }
    }

    /**
     * Basculer le statut actif d'une année scolaire
     */
    public function toggleSchoolYearStatus(int $id): void
    {
        $result = $this->schoolYearService->toggleStatus($id);

        if ($result['success']) {
            $this->success($result['message']);
        } else {
            $this->error($result['message']);
        }
    }

    /**
     * Demande de confirmation pour supprimer un taux
     */
    public function confirmDeleteRate(int $id): void
    {
        $rate = \App\Models\Rate::find($id);

        if (!$rate) {
            $this->error('Taux introuvable');
            return;
        }

        $this->dispatch('delete-rate-dialog', [
            'id' => $rate->id,
            'amount' => $rate->amount,
            'isDefault' => !$rate->is_changed,
        ]);
    }

    /**
     * Supprimer un taux
     */
    public function deleteRate(int $id): void
    {
        $result = $this->rateService->delete($id);

        if ($result['success']) {
            $this->dispatch('item-deleted', ['message' => $result['message']]);
            $this->resetPage();
        } else {
            $this->dispatch('delete-failed', ['message' => $result['message']]);
        }
    }

    /**
     * Basculer le statut d'un taux
     */
    public function toggleRateStatus(int $id): void
    {
        $result = $this->rateService->toggleStatus($id);

        if ($result['success']) {
            $this->success($result['message']);
        } else {
            $this->error($result['message']);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        $schoolId = School::DEFAULT_SCHOOL_ID();

        return view('livewire.application.v2.configuration.configuration-management-page', [
            'schoolYears' => $this->configType === 'school-year'
                ? $this->schoolYearService->getBySchool($schoolId)
                : collect(),
            'rates' => $this->configType === 'rate'
                ? $this->rateService->getBySchool($schoolId)
                : collect(),
        ]);
    }
}
