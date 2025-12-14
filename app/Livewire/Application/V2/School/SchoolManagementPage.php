<?php

namespace App\Livewire\Application\V2\School;

use App\Models\School;
use App\Repositories\SchoolRepository;
use App\Services\SchoolManagementService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SchoolManagementPage extends Component
{
    use WithPagination;

    // Filters
    #[Url(as: 'q')]
    public ?string $search = '';

    public string $sortBy = 'name';
    public bool $sortAsc = true;
    public int $perPage = 15;

    // Services injectés via boot
    protected SchoolRepository $schoolRepository;
    protected SchoolManagementService $schoolManagementService;

    protected $listeners = [
        'school-saved' => 'refreshData',
    ];

    /**
     * Injection de dépendances via boot
     */
    public function boot(
        SchoolRepository $schoolRepository,
        SchoolManagementService $schoolManagementService
    ): void {
        $this->schoolRepository = $schoolRepository;
        $this->schoolManagementService = $schoolManagementService;
    }

    public function refreshData()
    {
        $this->resetPage();
    }

    public function openCreateSchool()
    {
        $this->dispatch('open-create-school');
    }

    public function editSchool(int $id)
    {
        $this->dispatch('edit-school', id: $id);
    }

    public function editSchoolLogo(int $id)
    {
        $this->dispatch('edit-school-logo', id: $id);
    }

    public function viewSchoolUsers(int $schoolId)
    {
        return redirect()->route('admin.schools.users', $schoolId);
    }

    public function toggleSchoolStatus(int $schoolId)
    {
        try {
            $school = School::find($schoolId);

            if (!$school) {
                $this->dispatch('error-message', message: 'École introuvable');
                return;
            }

            $this->authorize('toggleStatus', $school);

            $this->schoolManagementService->toggleSchoolStatus($schoolId);

            $this->dispatch('success-message', message: 'Statut de l\'école modifié avec succès');
            $this->refreshData();
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la modification: ' . $e->getMessage());
            Log::error('Error toggling school status: ' . $e->getMessage());
        }
    }

    public function deleteSchool(int $schoolId)
    {
        try {
            $school = School::find($schoolId);

            if (!$school) {
                $this->dispatch('error-message', message: 'École introuvable');
                return;
            }

            $this->authorize('delete', $school);

            $this->schoolManagementService->deleteSchool($schoolId);

            $this->dispatch('success-message', message: 'École supprimée avec succès');
            $this->refreshData();
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $e->getMessage());
            Log::error('Error deleting school: ' . $e->getMessage());
        }
    }

    public function sortData(string $field)
    {
        if ($this->sortBy === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortBy = $field;
            $this->sortAsc = true;
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->sortBy = 'name';
        $this->sortAsc = true;
        $this->resetPage();
    }

    public function render()
    {
        $schools = $this->schoolRepository->getAllPaginated($this->perPage, $this->search);
        $stats = $this->schoolManagementService->getGeneralStats();

        return view('livewire.application.v2.school.school-management-page', [
            'schools' => $schools,
            'stats' => $stats,
        ]);
    }
}
