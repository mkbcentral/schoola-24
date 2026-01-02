<?php

namespace App\Livewire\Application\V3\Payment;

use App\Services\Student\StudentSearchService;
use Livewire\Component;

class PaymentManagementPage extends Component
{
    // Constantes
    private const MIN_SEARCH_LENGTH = 2;

    // Recherche d'élève
    public $search = '';
    public $searchResults = [];
    public $showDropdown = false;

    // Élève sélectionné
    public $selectedRegistrationId = null;
    public $registration = null;
    public $studentInfo = [];
    public $showForm = false;

    // Service
    private StudentSearchService $studentSearchService;

    public function boot(StudentSearchService $studentSearchService): void
    {
        $this->studentSearchService = $studentSearchService;
    }

    /**
     * Recherche d'élèves en temps réel
     */
    public function updatedSearch(): void
    {
        if (strlen(trim($this->search)) < self::MIN_SEARCH_LENGTH) {
            $this->resetSearchResults();
            return;
        }

        try {
            $this->searchResults = $this->studentSearchService->searchStudents(trim($this->search));
            $this->showDropdown = !empty($this->searchResults);
        } catch (\Exception $e) {
            $this->resetSearchResults();
            $this->notifyError('Erreur de recherche: ' . $e->getMessage());
        }
    }

    /**
     * Sélectionner un élève
     */
    public function selectStudent(int $registrationId, string $studentName): void
    {
        $this->selectedRegistrationId = $registrationId;
        $this->search = $studentName;
        $this->showDropdown = false;
        $this->loadStudentInfo();
        $this->showForm = true;
        
        // Notifier les composants enfants
        $this->dispatch('studentSelected', 
            registrationId: $this->selectedRegistrationId,
            registration: $this->registration,
            studentInfo: $this->studentInfo
        );
    }

    /**
     * Charger les informations de l'élève
     */
    private function loadStudentInfo(): void
    {
        if (!$this->selectedRegistrationId) {
            return;
        }

        try {
            $studentData = $this->studentSearchService->getStudentInfo($this->selectedRegistrationId);

            if (!$studentData) {
                $this->notifyError('Élève non trouvé');
                $this->resetStudent();
                return;
            }

            $this->registration = $studentData['registration'];
            $this->studentInfo = $studentData['info'];
        } catch (\Exception $e) {
            $this->notifyError('Erreur: ' . $e->getMessage());
            $this->resetStudent();
        }
    }

    /**
     * Réinitialiser complètement
     */
    public function resetStudent(): void
    {
        $this->selectedRegistrationId = null;
        $this->registration = null;
        $this->studentInfo = [];
        $this->search = '';
        $this->searchResults = [];
        $this->showDropdown = false;
        $this->showForm = false;
        
        // Notifier les composants enfants
        $this->dispatch('studentReset');
    }

    /**
     * Fermer le dropdown
     */
    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }

    // ============================================================
    // MÉTHODES HELPERS PRIVÉES
    // ============================================================

    /**
     * Réinitialiser les résultats de recherche
     */
    private function resetSearchResults(): void
    {
        $this->searchResults = [];
        $this->showDropdown = false;
    }

    /**
     * Envoyer une notification d'erreur
     */
    private function notifyError(string $message): void
    {
        $this->dispatch('notification', [
            'type' => 'error',
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.application.v3.payment.payment-management-page');
    }
}
