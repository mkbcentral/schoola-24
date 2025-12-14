<?php

namespace App\Livewire\Application\V2\Registration;

use App\DTOs\Registration\RegistrationFilterDTO;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Services\Registration\RegistrationService;
use Livewire\Component;
use Livewire\WithPagination;

class RegistrationListPage extends Component
{
    use WithPagination;

    // Filtres
    public $sectionId = null;
    public $optionId = null;
    public $classRoomId = null;
    public $gender = null;
    public $dateFrom = null;
    public $dateTo = null;
    public $isOld = null;
    public $abandoned = null;
    public $isRegistered = null;
    public $perPage = 15;
    public $search = '';

    // UI State
    public $showFilters = false;
    public $selectedRegistrationId = null;

    protected $queryString = [
        'sectionId' => ['except' => null, 'as' => 'section'],
        'optionId' => ['except' => null, 'as' => 'option'],
        'classRoomId' => ['except' => null, 'as' => 'classe'],
        'gender' => ['except' => null, 'as' => 'genre'],
    ];

    protected $listeners = [
        'registration-created' => '$refresh',
        'registration-updated' => '$refresh',
        'registration-deleted' => '$refresh',
    ];

    private RegistrationService $registrationService;

    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSectionId($value)
    {
        $this->optionId = null;
        $this->classRoomId = null;
        $this->resetPage();
    }

    public function updatedOptionId($value)
    {
        $this->classRoomId = null;
        $this->resetPage();
    }

    public function updatedClassRoomId()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'sectionId',
            'optionId',
            'classRoomId',
            'gender',
            'dateFrom',
            'dateTo',
            'isOld',
            'abandoned',
            'isRegistered',
            'search'
        ]);
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function deleteRegistration($id)
    {
        try {
            $this->registrationService->delete($id);
            $this->dispatch('success', message: 'Inscription supprimée avec succès');
            $this->dispatch('registration-deleted');
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    public function markAsAbandoned($id)
    {
        try {
            $this->registrationService->markAsAbandoned($id);
            $this->dispatch('success', message: 'Élève marqué comme ayant abandonné');
            $this->dispatch('registration-updated');
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    public function markAsNotAbandoned($id)
    {
        try {
            $this->registrationService->markAsNotAbandoned($id);
            $this->dispatch('success', message: 'Statut d\'abandon retiré');
            $this->dispatch('registration-updated');
        } catch (\Exception $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    public function render()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'section_id' => $this->sectionId,
            'option_id' => $this->optionId,
            'class_room_id' => $this->classRoomId,
            'gender' => $this->gender,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'is_old' => $this->isOld,
            'abandoned' => $this->abandoned,
            'is_registered' => $this->isRegistered,
        ]);

        $result = $this->registrationService->getFilteredWithStats($filter, $this->perPage);

        $sections = Section::orderBy('name')->get();
        $options = Option::query()
            ->when($this->sectionId, fn($q) => $q->where('section_id', $this->sectionId))
            ->orderBy('name')
            ->get();
        $classRooms = ClassRoom::query()
            ->when($this->optionId, fn($q) => $q->where('option_id', $this->optionId))
            ->orderBy('name')
            ->get();

        return view('livewire.application.v2.registration.registration-list-page', [
            'registrations' => $result['registrations'],
            'stats' => $result['stats'],
            'sections' => $sections,
            'options' => $options,
            'classRooms' => $classRooms,
        ]);
    }
}
