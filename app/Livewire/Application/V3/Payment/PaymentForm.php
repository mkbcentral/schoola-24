<?php

namespace App\Livewire\Application\V3\Payment;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\UpdatePaymentAction;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\ScolarFee;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentForm extends Component
{
    // Élève sélectionné
    public $registrationId = null;
    public $registration = null;
    public $studentInfo = [];

    // Formulaire de paiement
    public $paymentId = null;
    public $categoryFeeId = '';
    public $scolarFeeId = null;
    public $month = '';
    public $createdAt = '';
    public $isPaid = false;
    public $rateId = 1;

    // Informations du ScolarFee sélectionné
    public $selectedFeeInfo = null;

    // État du formulaire
    public $isEditing = false;

    // Actions
    private CreatePaymentAction $createPaymentAction;
    private UpdatePaymentAction $updatePaymentAction;

    protected function rules()
    {
        return [
            'categoryFeeId' => 'required|exists:category_fees,id',
            'month' => 'required|string',
            'createdAt' => 'required|date',
        ];
    }

    protected function messages()
    {
        return [
            'categoryFeeId.required' => 'Veuillez sélectionner une catégorie de frais.',
            'month.required' => 'Veuillez sélectionner un mois.',
            'createdAt.required' => 'La date est obligatoire.',
        ];
    }

    public function boot(
        CreatePaymentAction $createPaymentAction,
        UpdatePaymentAction $updatePaymentAction
    ): void {
        $this->createPaymentAction = $createPaymentAction;
        $this->updatePaymentAction = $updatePaymentAction;
    }

    public function mount(): void
    {
        $this->createdAt = now()->format('Y-m-d');
    }

    #[On('studentSelected')]
    public function handleStudentSelected($registrationId, $registration, $studentInfo): void
    {
        $this->registrationId = $registrationId;
        $this->registration = $registration;
        $this->studentInfo = $studentInfo;
        $this->resetForm();
    }

    #[On('editPayment')]
    public function handleEditPayment(int $paymentId): void
    {
        try {
            $payment = Payment::with('registration.student', 'scolarFee.categoryFee')->findOrFail($paymentId);

            if ($payment->is_paid) {
                $this->dispatch('error', ['message' => 'Ce paiement est déjà validé et ne peut être modifié']);
                return;
            }

            $this->loadPaymentForEditing($payment);

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur lors du chargement du paiement']);
        }
    }

    /**
     * Charger les données du paiement pour édition
     */
    private function loadPaymentForEditing(Payment $payment): void
    {
        $this->registrationId = $payment->registration_id;
        $this->registration = $payment->registration;
        $this->studentInfo = [
            'name' => $payment->registration->student->name ?? '',
            'code' => $payment->registration->code ?? '',
            'class_room' => $payment->registration->classRoom->getOriginalClassRoomName() ?? '',
            'option' => $payment->registration->classRoom->option->name ?? '',
        ];

        $this->paymentId = $payment->id;
        $this->categoryFeeId = $payment->scolarFee->category_fee_id ?? null;
        $this->scolarFeeId = $payment->scolar_fee_id;
        $this->month = $payment->month;
        $this->createdAt = $payment->created_at->format('Y-m-d');
        $this->isPaid = $payment->is_paid;
        $this->rateId = $payment->rate_id;

        if ($payment->scolarFee) {
            $this->selectedFeeInfo = [
                'amount' => $payment->scolarFee->amount,
                'currency' => $payment->scolarFee->categoryFee->currency ?? 'CDF',
                'category_name' => $payment->scolarFee->categoryFee->name ?? '',
            ];
        }

        $this->isEditing = true;
    }

    /**
     * Sauvegarder le paiement
     */
    public function save(): void
    {
        if (!$this->registrationId) {
            $this->dispatch('error', ['message' => 'Veuillez sélectionner un élève.']);
            return;
        }

        $this->validate();

        try {
            $this->isEditing ? $this->updatePayment() : $this->createPayment();
            $this->dispatch('paymentSaved');
            $this->dispatch('refreshStudentHistory');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur: ' . $e->getMessage()]);
        }
    }

    /**
     * Créer un nouveau paiement
     */
    protected function createPayment(): void
    {
        $result = $this->createPaymentAction->execute(
            $this->registrationId,
            $this->categoryFeeId,
            $this->month,
            [
                'created_at' => $this->createdAt,
                'is_paid' => $this->isPaid,
            ]
        );

        if (!$result['success']) {
            $this->dispatch('error', ['message' => $result['message'] ?? 'Erreur lors de la création']);
            return;
        }

        $this->dispatch('added', ['message' => $result]);
        $this->dispatch('paymentCreated');
        $this->dispatch('refreshStudentHistory');
    }

    /**
     * Mettre à jour un paiement existant
     */
    protected function updatePayment(): void
    {
        $payment = Payment::findOrFail($this->paymentId);

        if ($payment->is_paid && !$this->isPaid) {
            $this->dispatch('error', ['message' => 'Impossible de modifier un paiement déjà validé']);
            return;
        }

        $result = $this->updatePaymentAction->execute(
            $payment->id,
            $this->categoryFeeId,
            $this->month,
            [
                'created_at' => $this->createdAt,
                'is_paid' => $this->isPaid,
            ]
        );

        if (!$result['success']) {
            $this->dispatch('error', ['message' => $result['message'] ?? 'Erreur lors de la mise à jour']);
            return;
        }

        $this->dispatch('added', ['message' => $result]);
        $this->dispatch('paymentCreated');
        $this->dispatch('refreshStudentHistory');
    }

    /**
     * Annuler l'édition
     */
    public function cancel(): void
    {
        $this->resetForm();
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm(): void
    {
        $this->reset(['paymentId', 'categoryFeeId', 'scolarFeeId', 'month', 'isPaid', 'isEditing', 'selectedFeeInfo']);
        $this->createdAt = now()->format('Y-m-d');
        $this->rateId = 1;
        $this->resetValidation();
    }

    /**
     * Charger les informations du ScolarFee quand une catégorie est sélectionnée
     */
    public function updatedCategoryFeeId($categoryFeeId): void
    {
        if (!$categoryFeeId || !$this->registration) {
            $this->clearSelectedFeeInfo();
            return;
        }

        try {
            $scolarFee = $this->findScolarFee($categoryFeeId);

            if ($scolarFee) {
                $this->setSelectedFeeInfo($scolarFee);
            } else {
                $this->clearSelectedFeeInfo();
                $this->dispatch('error', ['message' => 'Aucun frais scolaire trouvé pour cette catégorie et cette classe']);
            }
        } catch (\Exception $e) {
            $this->clearSelectedFeeInfo();
            $this->logAndNotifyError('updatedCategoryFeeId', $e, $categoryFeeId);
        }
    }

    /**
     * Rechercher le ScolarFee correspondant
     */
    private function findScolarFee(int $categoryFeeId): ?ScolarFee
    {
        // Gérer le cas où registration est un tableau ou un objet
        $classRoomId = is_array($this->registration) 
            ? ($this->registration['class_room_id'] ?? null)
            : $this->registration->class_room_id ?? null;

        if (!$classRoomId) {
            return null;
        }

        return ScolarFee::with('categoryFee')
            ->where('category_fee_id', $categoryFeeId)
            ->where('class_room_id', $classRoomId)
            ->first();
    }

    /**
     * Définir les informations du frais sélectionné
     */
    private function setSelectedFeeInfo(ScolarFee $scolarFee): void
    {
        $this->selectedFeeInfo = [
            'amount' => $scolarFee->amount,
            'currency' => $scolarFee->categoryFee->currency ?? 'CDF',
            'category_name' => $scolarFee->categoryFee->name ?? '',
        ];
        $this->scolarFeeId = $scolarFee->id;

        // Utiliser le taux par défaut
        $this->rateId = Rate::DEFAULT_RATE_ID();
    }

    /**
     * Effacer les informations du frais sélectionné
     */
    private function clearSelectedFeeInfo(): void
    {
        $this->selectedFeeInfo = null;
        $this->scolarFeeId = null;
    }

    /**
     * Charger les catégories de frais
     */
    public function getCategoryFeesProperty()
    {
        return CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->orderBy('name')
            ->get();
    }

    /**
     * Liste des mois de l'année scolaire
     */
    public function getMonthsProperty(): array
    {
        return [
            ['value' => '9', 'label' => 'SEPTEMBRE'],
            ['value' => '10', 'label' => 'OCTOBRE'],
            ['value' => '11', 'label' => 'NOVEMBRE'],
            ['value' => '12', 'label' => 'DÉCEMBRE'],
            ['value' => '1', 'label' => 'JANVIER'],
            ['value' => '2', 'label' => 'FÉVRIER'],
            ['value' => '3', 'label' => 'MARS'],
            ['value' => '4', 'label' => 'AVRIL'],
            ['value' => '5', 'label' => 'MAI'],
            ['value' => '6', 'label' => 'JUIN'],
            ['value' => '7', 'label' => 'JUILLET'],
            ['value' => '8', 'label' => 'AOÛT'],
        ];
    }

    // ============================================================
    // MÉTHODES HELPERS
    // ============================================================

    private function logAndNotifyError(string $context, \Exception $e, mixed $data = null): void
    {
        Log::error("Erreur {$context}", [
            'error' => $e->getMessage(),
            'data' => $data,
            'registration' => $this->registration
        ]);
        
        $this->dispatch('error', ['message' => 'Erreur lors du chargement des informations: ' . $e->getMessage()]);
    }

    public function render()
    {
        return view('livewire.application.v3.payment.payment-form');
    }
}
