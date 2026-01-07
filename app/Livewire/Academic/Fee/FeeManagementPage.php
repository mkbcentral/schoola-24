<?php

namespace App\Livewire\Academic\Fee;

use App\Models\School;
use App\Services\Fee\CategoryRegistrationFeeService;
use App\Services\Fee\CategoryFeeService;
use App\Services\Fee\ScolarFeeService;
use App\Services\Fee\RegistrationFeeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FeeManagementPage extends Component
{
    use WithPagination;

    public string $activeTab = 'category-registration-fee';

    // Filters
    public ?int $selectedSchoolYearId = null;
    public ?int $selectedCategoryFeeId = null;
    public ?int $selectedCategoryRegistrationFeeId = null;
    public ?int $selectedOptionId = null;
    public ?int $selectedClassRoomId = null;

    // Scolar Fee Filters
    public ?int $filterScolarCategoryFeeId = null;
    public ?int $filterScolarClassRoomId = null;
    public int $perPage = 10;

    // Services injectés via boot
    protected CategoryRegistrationFeeService $categoryRegistrationFeeService;
    protected CategoryFeeService $categoryFeeService;
    protected ScolarFeeService $scolarFeeService;
    protected RegistrationFeeService $registrationFeeService;

    protected $listeners = [
        'category-registration-fee-saved' => 'refreshData',
        'category-fee-saved' => 'refreshData',
        'scolar-fee-saved' => 'refreshData',
        'registration-fee-saved' => 'refreshData',
    ];

    /**
     * Injection de dépendances via boot
     */
    public function boot(
        CategoryRegistrationFeeService $categoryRegistrationFeeService,
        CategoryFeeService $categoryFeeService,
        ScolarFeeService $scolarFeeService,
        RegistrationFeeService $registrationFeeService
    ): void {
        $this->categoryRegistrationFeeService = $categoryRegistrationFeeService;
        $this->categoryFeeService = $categoryFeeService;
        $this->scolarFeeService = $scolarFeeService;
        $this->registrationFeeService = $registrationFeeService;
    }

    public function refreshData()
    {
        // Force refresh du composant
        $this->resetPage();
    }

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatedFilterScolarCategoryFeeId()
    {
        $this->resetPage();
    }

    public function updatedFilterScolarClassRoomId()
    {
        $this->resetPage();
    }

    public function resetScolarFilters()
    {
        $this->filterScolarCategoryFeeId = null;
        $this->filterScolarClassRoomId = null;
        $this->resetPage();
    }

    // Open create modals
    public function openCreateCategoryRegistrationFee()
    {
        $this->dispatch('open-create-category-registration-fee');
    }

    public function openCreateCategoryFee()
    {
        $this->dispatch('open-create-category-fee');
    }

    public function openCreateScolarFee()
    {
        $this->dispatch('open-create-scolar-fee');
    }

    public function openCreateRegistrationFee()
    {
        $this->dispatch('open-create-registration-fee');
    }

    // Edit methods
    public function editCategoryRegistrationFee(int $id)
    {
        $this->dispatch('edit-category-registration-fee', id: $id);
    }

    public function editCategoryFee(int $id)
    {
        $this->dispatch('edit-category-fee', id: $id);
    }

    public function editScolarFee(int $id)
    {
        $this->dispatch('edit-scolar-fee', id: $id);
    }

    public function editRegistrationFee(int $id)
    {
        $this->dispatch('edit-registration-fee', id: $id);
    }

    public function deleteCategoryRegistrationFee(int $id)
    {
        try {
            if ($this->categoryRegistrationFeeService->delete($id)) {
                $this->dispatch('success-message', message: 'Catégorie supprimée avec succès');
                $this->refreshData();
            } else {
                $this->dispatch('error-message', message: 'Impossible de supprimer cette catégorie');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function deleteCategoryFee(int $id)
    {
        try {
            if ($this->categoryFeeService->delete($id)) {
                $this->dispatch('success-message', message: 'Catégorie supprimée avec succès');
                $this->refreshData();
            } else {
                $this->dispatch('error-message', message: 'Impossible de supprimer cette catégorie');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function deleteScolarFee(int $id)
    {
        try {
            if ($this->scolarFeeService->delete($id)) {
                $this->dispatch('success-message', message: 'Frais supprimé avec succès');
                $this->refreshData();
            } else {
                $this->dispatch('error-message', message: 'Impossible de supprimer ce frais');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function deleteRegistrationFee(int $id)
    {
        try {
            if ($this->registrationFeeService->delete($id)) {
                $this->dispatch('success-message', message: 'Frais supprimé avec succès');
                $this->refreshData();
            } else {
                $this->dispatch('error-message', message: 'Impossible de supprimer ce frais');
            }
        } catch (\Exception $e) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Get statistics for all fee types
        $data['statistics'] = [
            'categoryRegistrationFee' => $this->categoryRegistrationFeeService->getStatistics(School::DEFAULT_SCHOOL_ID()),
            'categoryFee' => $this->categoryFeeService->getStatistics(School::DEFAULT_SCHOOL_ID()),
            'scolarFee' => ['total' => 0, 'changed' => 0, 'total_amount' => 0],
            'registrationFee' => ['total' => 0, 'total_amount' => 0, 'options_count' => 0],
        ];

        // Calculate totals for scolar and registration fees
        try {
            $allScolarFees = $this->scolarFeeService->getAll();
            $data['statistics']['scolarFee']['total'] = $allScolarFees->count();

            $allRegistrationFees = $this->registrationFeeService->getAll();
            $data['statistics']['registrationFee']['total'] = $allRegistrationFees->count();
        } catch (\Exception $e) {
            // Continue with default values if error
        }

        // Initialize filter variables for all cases
        $data['availableCategoryFees'] = collect();
        $data['availableClassRooms'] = collect();

        // Load only data for active tab to improve performance
        switch ($this->activeTab) {
            case 'category-registration-fee':
                $data['categoryRegistrationFees'] = $this->categoryRegistrationFeeService->getBySchool(School::DEFAULT_SCHOOL_ID());
                $data['categoryFees'] = collect();
                $data['scolarFees'] = collect();
                $data['registrationFees'] = collect();
                break;

            case 'category-fee':
                $data['categoryRegistrationFees'] = collect();
                $data['categoryFees'] = $this->categoryFeeService->getBySchool(School::DEFAULT_SCHOOL_ID());
                $data['scolarFees'] = collect();
                $data['registrationFees'] = collect();
                break;

            case 'scolar-fee':
                // Charger les catégories pour le filtre
                $data['availableCategoryFees'] = $this->categoryFeeService->getBySchool(School::DEFAULT_SCHOOL_ID());

                // Construire la requête avec filtres
                $query = \App\Models\ScolarFee::with(['categoryFee', 'classRoom'])
                    ->orderBy('name');

                if ($this->filterScolarCategoryFeeId) {
                    $query->where('category_fee_id', $this->filterScolarCategoryFeeId);
                }

                if ($this->filterScolarClassRoomId) {
                    $query->where('class_room_id', $this->filterScolarClassRoomId);
                }

                $data['scolarFees'] = $query->paginate($this->perPage);

                // Charger toutes les classes pour le filtre
                $data['availableClassRooms'] = \App\Models\ClassRoom::orderBy('name')->get();

                $data['categoryRegistrationFees'] = collect();
                $data['categoryFees'] = collect();
                $data['registrationFees'] = collect();
                break;

            case 'registration-fee':
                if ($this->selectedCategoryRegistrationFeeId) {
                    $data['registrationFees'] = $this->registrationFeeService
                        ->getByCategoryRegistrationFee($this->selectedCategoryRegistrationFeeId);
                } else {
                    $data['registrationFees'] = $this->registrationFeeService->getAll();
                }
                $data['categoryRegistrationFees'] = collect();
                $data['categoryFees'] = collect();
                $data['scolarFees'] = collect();
                break;

            default:
                $data['categoryRegistrationFees'] = collect();
                $data['categoryFees'] = collect();
                $data['scolarFees'] = collect();
                $data['registrationFees'] = collect();
        }

        return view('livewire.application.v2.fee.fee-management-page', $data);
    }
}
