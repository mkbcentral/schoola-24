<?php

namespace App\Livewire\Application\Payment;

use App\Services\Payment\PaymentHistoryService;
use App\Services\Student\StudentSearchService;
use Livewire\Component;

class QuickPaymentPage extends Component
{

    // Recherche d'élève pour formulaire
    public $search = '';
    public $searchResults = [];
    public $showDropdown = false;

    // Élève sélectionné
    public $selectedRegistrationId = null;
    public $registration = null;
    public $studentInfo = [];
    public $studentPaymentHistory = [];
    public $popoverContent = '';

    // Services
    private StudentSearchService $studentSearchService;
    private PaymentHistoryService $paymentHistoryService;

    protected $listeners = [
        'refreshStudentHistory' => 'loadStudentPaymentHistory',
        'editPayment' => 'handleEditPayment',
        'paymentDeleted' => 'handlePaymentDeleted',
    ];

    public function boot(
        StudentSearchService $studentSearchService,
        PaymentHistoryService $paymentHistoryService
    ): void {
        $this->studentSearchService = $studentSearchService;
        $this->paymentHistoryService = $paymentHistoryService;
    }

    public function mount(): void
    {
        // Initialisation si nécessaire
    }



    /**
     * Recherche d'élèves en temps réel
     */
    public function updatedSearch(): void
    {
        if (strlen(trim($this->search)) < 2) {
            $this->searchResults = [];
            $this->showDropdown = false;

            if (strlen(trim($this->search)) === 0) {
                $this->resetStudent();
            }

            return;
        }

        try {
            $this->searchResults = $this->studentSearchService->searchStudents(trim($this->search));
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
    public function selectStudent(int $registrationId, string $studentName): void
    {
        $this->selectedRegistrationId = $registrationId;
        $this->search = $studentName;
        $this->showDropdown = false;
        $this->loadStudentInfo();
        $this->dispatch('studentSelected', registrationId: $registrationId);
    }

    /**
     * Charger les informations de l'élève
     */
    public function loadStudentInfo(): void
    {
        if (!$this->selectedRegistrationId) {
            return;
        }

        try {
            $studentData = $this->studentSearchService->getStudentInfo($this->selectedRegistrationId);

            if (!$studentData) {
                $this->dispatch('error', ['message' => 'Élève non trouvé']);
                $this->resetStudent();
                return;
            }

            $this->registration = $studentData['registration'];
            $this->studentInfo = $studentData['info'];

            $this->loadStudentPaymentHistory();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur: ' . $e->getMessage()]);
            $this->resetStudent();
        }
    }

    /**
     * Charger l'historique des paiements de l'élève pour l'année scolaire en cours
     */
    public function loadStudentPaymentHistory(): void
    {
        if (!$this->selectedRegistrationId) {
            $this->studentPaymentHistory = [];
            $this->popoverContent = '';
            return;
        }

        $this->studentPaymentHistory = $this->paymentHistoryService->getStudentPaymentHistory(
            $this->selectedRegistrationId
        );

        // Construire le contenu du tooltip (texte simple pour Bootstrap tooltip)
        if (!empty($this->studentPaymentHistory)) {
            $lines = [];
            $lines[] = "📋 HISTORIQUE DES PAIEMENTS";
            $lines[] = "─────────────────────────";
            foreach ($this->studentPaymentHistory as $history) {
                $status = $history['is_paid'] ? '✅' : '⏳';
                $lines[] = "{$status} {$history['date']} - {$history['category']} ({$history['month']})";
            }
            $lines[] = "─────────────────────────";
            $lines[] = "Total: " . count($this->studentPaymentHistory) . " paiement(s)";
            $this->popoverContent = implode("\n", $lines);
        } else {
            $this->popoverContent = 'Aucun historique de paiement disponible';
        }
    }



    /**
     * Gérer l'édition d'un paiement - Mettre à jour les infos de l'élève
     */
    public function handleEditPayment(int $id, int $registration_id, ?int $category_fee_id, string $month, string $created_at, bool $is_paid): void
    {
        \Log::info('QuickPaymentPage: handleEditPayment called', [
            'registration_id' => $registration_id,
            'current_registration_id' => $this->selectedRegistrationId
        ]);

        // Charger les infos de l'élève du paiement sélectionné
        $this->selectedRegistrationId = $registration_id;
        $this->loadStudentInfo();

        // Mettre à jour le champ de recherche avec le nom de l'élève
        if (!empty($this->studentInfo['name'])) {
            $this->search = $this->studentInfo['name'];
        }
    }

    /**
     * Gérer la suppression d'un paiement
     */
    public function handlePaymentDeleted(): void
    {
        // Réinitialiser complètement l'état
        $this->resetStudent();
    }

    /**
     * Réinitialiser l'élève
     */
    public function resetStudent(): void
    {
        $this->selectedRegistrationId = null;
        $this->registration = null;
        $this->studentInfo = [];
        $this->search = '';
        $this->searchResults = [];
        $this->showDropdown = false;
        $this->dispatch('resetPaymentForm');
    }

    /**
     * Fermer le dropdown
     */
    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }



    public function render()
    {
        return view('livewire.application.payment.quick-payment-page-modern');
    }
}
