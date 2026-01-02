<?php

namespace App\Livewire\Application\V2\Student;

use App\Models\Registration;
use App\Services\Student\StudentInfoService;
use Livewire\Component;

class StudentInfoPage extends Component
{
    public $search = '';

    public $selectedRegistrationId = null;

    public $studentInfo = null;

    public $searchResults = [];

    public $showDropdown = false;

    public $selectedCategoryId = null;

    public $availableCategories = [];

    protected StudentInfoService $studentInfoService;

    public function boot(StudentInfoService $studentInfoService)
    {
        $this->studentInfoService = $studentInfoService;
    }

    public function mount()
    {
        $this->loadAvailableCategories();
    }

    /**
     * Charger les catégories disponibles
     */
    public function loadAvailableCategories()
    {
        $this->availableCategories = $this->studentInfoService->getAvailableCategories();

        // Sélectionner Minerval par défaut
        $minerval = collect($this->availableCategories)->firstWhere('is_minerval', true);
        if ($minerval) {
            $this->selectedCategoryId = $minerval['id'];
        }
    }

    /**
     * Recherche d'élèves en temps réel
     */
    public function updatedSearch()
    {
        // Ne réinitialiser que si la recherche est vide ou trop courte
        if (strlen(trim($this->search)) < 2) {
            $this->searchResults = [];
            $this->showDropdown = false;
            // Ne pas réinitialiser les infos si la barre n'est pas complètement vide
            if (strlen(trim($this->search)) === 0) {
                $this->selectedRegistrationId = null;
                $this->studentInfo = null;
            }

            return;
        }

        try {
            $currentSchoolYearId = \App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

            // Nettoyer le terme de recherche
            $searchTerm = trim($this->search);

            // Recherche plus flexible avec plusieurs critères
            $this->searchResults = Registration::with(['student', 'classRoom', 'classRoom.option'])
                ->whereHas('student', function ($query) use ($searchTerm) {
                    // Recherche insensible à la casse et aux accents
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('name', 'LIKE', '%' . strtoupper($searchTerm) . '%')
                            ->orWhere('name', 'LIKE', '%' . ucwords(strtolower($searchTerm)) . '%');
                    });
                })
                ->where('school_year_id', $currentSchoolYearId)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($registration) {
                    return [
                        'id' => $registration->id,
                        'student_name' => $registration->student->name,
                        'code' => $registration->code,
                        'class_room' => $registration->classRoom?->getOriginalClassRoomName(),
                        'option' => $registration->classRoom?->option?->name,
                    ];
                })
                ->toArray();

            $this->showDropdown = true;
        } catch (\Exception $e) {
            $this->searchResults = [];
            $this->showDropdown = false;
            $this->dispatch('error', ['message' => 'Erreur de recherche: ' . $e->getMessage()]);
        }
    }

    /**
     * Sélectionner un élève
     */
    public function selectStudent($registrationId, $studentName)
    {
        $this->selectedRegistrationId = $registrationId;
        $this->search = $studentName;
        $this->showDropdown = false;
        $this->loadStudentInfo();
    }

    /**
     * Charger les informations complètes de l'élève
     */
    public function loadStudentInfo()
    {
        if (! $this->selectedRegistrationId) {
            $this->dispatch('error', ['message' => 'Aucun élève sélectionné']);

            return;
        }

        try {
            $this->studentInfo = $this->studentInfoService->getStudentCompleteInfo(
                $this->selectedRegistrationId,
                $this->selectedCategoryId
            );

            if (! $this->studentInfo) {
                $this->dispatch('error', ['message' => 'Élève non trouvé']);
                $this->resetStudentInfo();
            }
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur: ' . $e->getMessage()]);
            $this->resetStudentInfo();
        }
    }

    /**
     * Changer la catégorie de frais
     */
    public function updatedSelectedCategoryId()
    {
        if ($this->selectedRegistrationId) {
            $this->loadStudentInfo();
        }
    }

    /**
     * Réinitialiser les informations
     */
    public function resetStudentInfo()
    {
        $this->selectedRegistrationId = null;
        $this->studentInfo = null;
        $this->search = '';
        $this->searchResults = [];
        $this->showDropdown = false;
    }

    /**
     * Fermer le dropdown
     */
    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.application.v2.student.student-info-page');
    }
}
