<?php

namespace App\Livewire\Configuration\System;

use App\Livewire\Traits\WithFlashMessages;
use App\Models\School;
use App\Services\Configuration\SectionService;
use App\Services\Configuration\OptionService;
use App\Services\Configuration\ClassRoomService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SectionManagementPage extends Component
{
    use WithPagination;
    use WithFlashMessages;

    // Type de configuration actif
    public string $configType = 'section';

    // Services injectés via boot
    protected SectionService $sectionService;
    protected OptionService $optionService;
    protected ClassRoomService $classRoomService;

    // Listeners
    protected $listeners = [
        'sectionSaved' => 'handleSectionSaved',
        'optionSaved' => 'handleOptionSaved',
        'classRoomSaved' => 'handleClassRoomSaved',
    ];

    protected $paginationTheme = 'bootstrap';

    /**
     * Injection de dépendances via boot
     */
    public function boot(
        SectionService $sectionService,
        OptionService $optionService,
        ClassRoomService $classRoomService
    ): void {
        $this->sectionService = $sectionService;
        $this->optionService = $optionService;
        $this->classRoomService = $classRoomService;
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

        if ($this->configType === 'section') {
            $this->dispatch('openSectionModal');
        } elseif ($this->configType === 'option') {
            $this->dispatch('openOptionModal');
        } else {
            $this->dispatch('openClassRoomModal');
        }
    }

    /**
     * Ouvrir le modal pour éditer
     */
    public function openEditModal(int $id): void
    {
        $this->clearMessage();

        if ($this->configType === 'section') {
            $this->dispatch('openSectionEditModal', id: $id);
        } elseif ($this->configType === 'option') {
            $this->dispatch('openOptionEditModal', id: $id);
        } else {
            $this->dispatch('openClassRoomEditModal', id: $id);
        }
    }

    /**
     * Gérer la sauvegarde d'une section
     */
    public function handleSectionSaved(array $data): void
    {
        if ($data['type'] === 'success') {
            $this->success($data['message']);
            // Vider le cache des sections
            \Illuminate\Support\Facades\Cache::forget('sections_school_' . Auth::user()->school->id);
        } else {
            $this->error($data['message']);
        }
        $this->resetPage();
    }

    /**
     * Gérer la sauvegarde d'une option
     */
    public function handleOptionSaved(array $data): void
    {
        if ($data['type'] === 'success') {
            $this->success($data['message']);
            // Vider le cache des options
            \Illuminate\Support\Facades\Cache::flush();
        } else {
            $this->error($data['message']);
        }
        $this->resetPage();
    }

    /**
     * Gérer la sauvegarde d'une classe
     */
    public function handleClassRoomSaved(array $data): void
    {
        if ($data['type'] === 'success') {
            $this->success($data['message']);
            // Vider le cache des classes
            \Illuminate\Support\Facades\Cache::flush();
        } else {
            $this->error($data['message']);
        }
        $this->resetPage();
    }

    /**
     * Demande de confirmation pour supprimer une section
     */
    public function confirmDeleteSection(int $id): void
    {
        $section = \App\Models\Section::find($id);

        if (!$section) {
            $this->error('Section introuvable');
            return;
        }

        $this->dispatch('delete-section-dialog', [
            'id' => $section->id,
            'name' => $section->name,
        ]);
    }

    /**
     * Supprimer une section
     */
    public function deleteSection(int $id): void
    {
        $result = $this->sectionService->delete($id);

        if ($result['success']) {
            $this->dispatch('item-deleted', ['message' => $result['message']]);
            $this->resetPage();
        } else {
            $this->dispatch('delete-failed', ['message' => $result['message']]);
        }
    }

    /**
     * Demande de confirmation pour supprimer une option
     */
    public function confirmDeleteOption(int $id): void
    {
        $option = \App\Models\Option::find($id);

        if (!$option) {
            $this->error('Option introuvable');
            return;
        }

        $this->dispatch('delete-option-dialog', [
            'id' => $option->id,
            'name' => $option->name,
        ]);
    }

    /**
     * Supprimer une option
     */
    public function deleteOption(int $id): void
    {
        $result = $this->optionService->delete($id);

        if ($result['success']) {
            $this->dispatch('item-deleted', ['message' => $result['message']]);
            $this->resetPage();
        } else {
            $this->dispatch('delete-failed', ['message' => $result['message']]);
        }
    }

    /**
     * Demande de confirmation pour supprimer une classe
     */
    public function confirmDeleteClassRoom(int $id): void
    {
        $classRoom = \App\Models\ClassRoom::find($id);

        if (!$classRoom) {
            $this->error('Classe introuvable');
            return;
        }

        $this->dispatch('delete-class-room-dialog', [
            'id' => $classRoom->id,
            'name' => $classRoom->name,
        ]);
    }

    /**
     * Supprimer une classe
     */
    public function deleteClassRoom(int $id): void
    {
        $result = $this->classRoomService->delete($id);

        if ($result['success']) {
            $this->dispatch('item-deleted', ['message' => $result['message']]);
            $this->resetPage();
        } else {
            $this->dispatch('delete-failed', ['message' => $result['message']]);
        }
    }

    public function render()
    {
        // Récupérer l'école courante
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $school = $user->school;

        // Charger les données selon le type de configuration
        if ($this->configType === 'section') {
            $items = $this->sectionService->getBySchool($school->id);
        } elseif ($this->configType === 'option') {
            $sections = $this->sectionService->getBySchool($school->id);
            $items = \App\Models\Option::with(['section'])
                ->whereHas('section', fn($q) => $q->where('school_id', $school->id))
                ->paginate(10);
        } else {
            $items = \App\Models\ClassRoom::with(['option.section'])
                ->whereHas('option.section', fn($q) => $q->where('school_id', $school->id))
                ->paginate(10);
        }

        return view('livewire.application.v2.configuration.section-management-page', [
            'items' => $items,
            'school' => $school,
        ]);
    }
}
