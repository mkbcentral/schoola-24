<?php

namespace App\Livewire\Application\V2\Report;

use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Section;
use App\Services\StudentDebtService;
use Livewire\Component;
use Livewire\WithPagination;

class ListStudentDebtPage extends Component
{
    use WithPagination;

    // Filtres
    public $sectionId = null;
    public $optionId = null;
    public $classRoomId = null;
    public $categoryFeeId = null;
    public $minMonthsUnpaid = 2;
    public $search = '';

    // Données
    public $sections = [];
    public $options = [];
    public $classRooms = [];
    public $categories = [];
    public $currency = 'USD';
    public $studentsWithDebt = [];
    public $statistics = [];

    // UI
    public $showFilters = true;
    public $selectedStudent = null;

    protected StudentDebtService $debtService;

    public function boot(StudentDebtService $debtService)
    {
        $this->debtService = $debtService;
    }

    public function mount()
    {
        $this->sections = Section::orderBy('name')->get();
        $this->loadCategories();
        $this->loadData();
    }

    public function loadCategories()
    {
        try {
            $categories = \App\Models\CategoryFee::where('school_year_id', \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('school_id', \App\Models\School::DEFAULT_SCHOOL_ID())
                ->orderBy('name')
                ->get();

            $this->categories = $categories->toArray();

            // Sélectionner Minerval par défaut
            $minerval = $categories->first(fn($cat) => stripos($cat->name, 'MINERVAL') !== false);
            if ($minerval) {
                $this->categoryFeeId = $minerval->id;
                $this->currency = $minerval->currency ?? 'USD';
            } elseif ($categories->isNotEmpty()) {
                $this->categoryFeeId = $categories->first()->id;
                $this->currency = $categories->first()->currency ?? 'USD';
            }
        } catch (\Exception $e) {
            \Log::error('Error loading categories: ' . $e->getMessage());
            $this->categories = [];
        }
    }

    public function updatedSectionId()
    {
        $this->optionId = null;
        $this->classRoomId = null;
        $this->loadOptions();
        $this->loadData();
    }

    public function updatedOptionId()
    {
        $this->classRoomId = null;
        $this->loadClassRooms();
        $this->loadData();
    }

    public function updatedClassRoomId()
    {
        $this->loadData();
    }

    public function updatedCategoryFeeId()
    {
        // Mettre à jour la devise quand on change de catégorie
        $category = collect($this->categories)->firstWhere('id', $this->categoryFeeId);
        if ($category) {
            $this->currency = $category['currency'] ?? 'USD';
        }
        $this->loadData();
    }

    public function updatedMinMonthsUnpaid()
    {
        $this->loadData();
    }

    public function updatedSearch()
    {
        $this->loadData();
    }

    public function loadOptions()
    {
        if ($this->sectionId) {
            $this->options = Option::where('section_id', $this->sectionId)
                ->orderBy('name')
                ->get();
        } else {
            $this->options = [];
        }
    }

    public function loadClassRooms()
    {
        if ($this->optionId) {
            $this->classRooms = ClassRoom::where('option_id', $this->optionId)
                ->orderBy('name')
                ->get();
        } else {
            $this->classRooms = [];
        }
    }

    public function loadData()
    {
        try {
            if (!$this->categoryFeeId) {
                $this->studentsWithDebt = [];
                $this->statistics = $this->getDefaultStatistics();
                return;
            }

            // Charger les élèves avec dettes
            $students = $this->debtService->getStudentsWithDebt(
                $this->sectionId,
                $this->optionId,
                $this->classRoomId,
                $this->categoryFeeId,
                $this->minMonthsUnpaid
            );

            // Appliquer le filtre de recherche
            if ($this->search) {
                $students = $students->filter(function ($student) {
                    return str_contains(
                        strtolower($student->studentName),
                        strtolower($this->search)
                    ) || str_contains(
                        strtolower($student->studentCode),
                        strtolower($this->search)
                    );
                });
            }

            // Convertir les DTOs en tableaux
            $this->studentsWithDebt = $students->map(fn($student) => $student->toArray())->toArray();

            // Charger les statistiques
            $this->statistics = $this->debtService->getDebtStatistics(
                $this->sectionId,
                $this->optionId,
                $this->classRoomId,
                $this->categoryFeeId
            );
        } catch (\Exception $e) {
            \Log::error('Error loading debt data: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            $this->studentsWithDebt = [];
            $this->statistics = $this->getDefaultStatistics();
            session()->flash('error', 'Erreur lors du chargement des données: ' . $e->getMessage());
        }
    }

    private function getDefaultStatistics(): array
    {
        return [
            'total_students' => 0,
            'total_debt_amount' => 0,
            'average_months_unpaid' => 0,
            'max_months_unpaid' => 0,
            'students_with_2_months' => 0,
            'students_with_3_months' => 0,
            'students_with_4_plus_months' => 0,
        ];
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetFilters()
    {
        $this->sectionId = null;
        $this->optionId = null;
        $this->classRoomId = null;
        $this->minMonthsUnpaid = 2;
        $this->search = '';
        $this->options = [];
        $this->classRooms = [];

        // Réinitialiser à Minerval par défaut
        $minerval = collect($this->categories)->first(fn($cat) => stripos($cat['name'], 'MINERVAL') !== false);
        if ($minerval) {
            $this->categoryFeeId = $minerval['id'];
            $this->currency = $minerval['currency'] ?? 'USD';
        }

        $this->loadData();
    }

    public function viewStudentDetails($studentId)
    {
        $this->selectedStudent = collect($this->studentsWithDebt)
            ->firstWhere('student_id', $studentId);
    }

    public function closeStudentDetails()
    {
        $this->selectedStudent = null;
    }

    public function exportData()
    {
        $data = $this->debtService->exportDebtData(
            $this->sectionId,
            $this->optionId,
            $this->classRoomId,
            $this->categoryFeeId
        );

        // Ici vous pouvez implementer l'export CSV/Excel
        // Pour l'instant, on declenche juste un evenement
        $this->dispatch('export-debt-data', ['data' => $data]);
    }

    public function render()
    {
        return view('livewire.application.v2.report.list-student-debt-page');
    }
}
