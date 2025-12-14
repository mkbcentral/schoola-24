<?php

namespace App\Livewire\Application\Admin\School;

use App\Models\School;
use App\Repositories\SchoolRepository;
use App\Services\SchoolManagementService;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SchoolListPage extends Component
{
    use WithPagination;

    protected $listeners = ['schoolDataRefreshed' => '$refresh'];

    public int $per_page = 15;

    #[Url(as: 'q')]
    public $q = '';

    #[Url(as: 'sortBy')]
    public $sortBy = 'name';

    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
    }

    public function createSchool()
    {
        return redirect()->route('admin.schools.create');
    }

    public function editSchool(int $schoolId)
    {
        return redirect()->route('admin.schools.edit', $schoolId);
    }

    public function viewSchoolUsers(int $schoolId)
    {
        return redirect()->route('admin.schools.users', $schoolId);
    }

    public function toggleSchoolStatus(int $schoolId): void
    {
        try {
            $this->authorize('toggleStatus', School::find($schoolId));
            $schoolManagementService = app(SchoolManagementService::class);
            $schoolManagementService->toggleSchoolStatus($schoolId);
            $this->dispatch('added', ['message' => 'Statut de l\'école modifié avec succès.']);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function deleteSchool(int $schoolId): void
    {
        try {
            $school = School::find($schoolId);
            $this->authorize('delete', $school);
            
            $schoolManagementService = app(SchoolManagementService::class);
            $schoolManagementService->deleteSchool($schoolId);
            $this->dispatch('added', ['message' => 'École supprimée avec succès.']);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render()
    {
        $schoolRepository = app(SchoolRepository::class);
        $schoolManagementService = app(SchoolManagementService::class);
        
        $schools = $schoolRepository->getAllPaginated($this->per_page, $this->q);
        $stats = $schoolManagementService->getGeneralStats();

        return view('livewire.application.admin.school.school-list-page', [
            'schools' => $schools,
            'stats' => $stats,
        ]);
    }
}
