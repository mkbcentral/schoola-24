<?php

namespace App\Livewire\Application\Payment;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\UpdatePaymentAction;
use App\Livewire\Forms\PaymentForm;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\ScolarFee;
use App\Services\CategoryFee\CategoryFeeService;
use App\Services\Payment\PaymentHistoryService;
use App\Services\ScolarFee\ScolarFeeService;
use Livewire\Component;

class PaymentFormComponent extends Component
{
    // Mode édition
    public ?int $paymentId = null;
    public bool $isEditMode = false;

    // Élève sélectionné
    public ?int $registrationId = null;
    public ?Registration $registration = null;

    // Formulaire de paiement
    public PaymentForm $form;
    public ?int $selectedCategoryFeeId = null;
    public ?ScolarFee $scolarFee = null;
    public bool $isPaid = false;

    // Données disponibles
    public $categoryFees = [];

    // Services
    private CategoryFeeService $categoryFeeService;
    private ScolarFeeService $scolarFeeService;
    private PaymentHistoryService $paymentHistoryService;

    protected $listeners = [
        'studentSelected' => 'handleStudentSelected',
        'resetPaymentForm' => 'resetForm',
        'editPayment' => 'handleEditPayment',
    ];

    public function boot(
        CategoryFeeService $categoryFeeService,
        ScolarFeeService $scolarFeeService,
        PaymentHistoryService $paymentHistoryService
    ): void {
        $this->categoryFeeService = $categoryFeeService;
        $this->scolarFeeService = $scolarFeeService;
        $this->paymentHistoryService = $paymentHistoryService;
    }

    public function mount(?int $registrationId = null): void
    {
        $this->registrationId = $registrationId;
        $this->initFormFields();
        $this->loadCategoryFees();

        if ($this->registrationId) {
            $this->loadRegistration();
        }
    }

    /**
     * Charger les catégories de frais
     */
    public function loadCategoryFees(): void
    {
        $this->categoryFees = $this->categoryFeeService->getAllCategoryFees();
    }

    /**
     * Initialiser les champs du formulaire
     */
    public function initFormFields(): void
    {
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
        $this->form->month = date('m');
        $this->isPaid = false;
    }

    /**
     * Charger l'inscription
     */
    public function loadRegistration(): void
    {
        if (!$this->registrationId) {
            return;
        }

        $this->registration = Registration::with(['student', 'classRoom'])->find($this->registrationId);
    }

    /**
     * Gérer la sélection d'un élève
     */
    public function handleStudentSelected(int $registrationId): void
    {
        $this->registrationId = $registrationId;
        $this->loadRegistration();
        $this->initFormFields();
    }

    /**
     * Mise à jour de la catégorie de frais
     */
    public function updatedSelectedCategoryFeeId($val): void
    {
        if (!$this->registration) {
            return;
        }

        $this->scolarFee = $this->scolarFeeService->getScolarFee($val, $this->registration->class_room_id);

        if (!$this->scolarFee) {
            $this->dispatch('error', ['message' => 'Aucun frais trouvé pour cette catégorie et cette classe']);
        }
    }

    /**
     * Gérer l'édition d'un paiement
     */
    public function handleEditPayment(int $id, int $registration_id, ?int $category_fee_id, string $month, string $created_at, bool $is_paid): void
    {
        \Log::info('handleEditPayment called', [
            'id' => $id,
            'registration_id' => $registration_id,
            'category_fee_id' => $category_fee_id
        ]);

        // Charger le paiement avec toutes ses relations
        $payment = Payment::with(['registration.student', 'registration.classRoom', 'scolarFee.categoryFee'])
            ->find($id);

        if (!$payment) {
            $this->dispatch('error', ['message' => 'Paiement non trouvé']);
            \Log::error('Payment not found', ['id' => $id]);
            return;
        }

        // Charger l'inscription complète (CRITIQUE pour afficher le formulaire)
        $this->registrationId = $registration_id;
        $this->registration = Registration::with(['student', 'classRoom'])->find($registration_id);

        if (!$this->registration) {
            $this->dispatch('error', ['message' => 'Inscription non trouvée']);
            \Log::error('Registration not found', ['registration_id' => $registration_id]);
            return;
        }

        // Activer le mode édition
        $this->isEditMode = true;
        $this->paymentId = $payment->id;

        // Remplir le formulaire avec les données du paiement
        $this->selectedCategoryFeeId = $category_fee_id;
        $this->form->created_at = $created_at;
        $this->form->month = $this->convertMonthLabelToNumber($month);
        $this->isPaid = $is_paid;

        // Charger le frais scolaire si on a un category_fee_id
        if ($this->selectedCategoryFeeId && $this->registration) {
            $this->scolarFee = $this->scolarFeeService->getScolarFee(
                $this->selectedCategoryFeeId,
                $this->registration->class_room_id
            );
        }

        \Log::info('Payment edit mode activated successfully', [
            'paymentId' => $this->paymentId,
            'registrationId' => $this->registrationId,
            'isEditMode' => $this->isEditMode,
            'hasRegistration' => !is_null($this->registration),
            'selectedCategoryFeeId' => $this->selectedCategoryFeeId,
            'hasScolarFee' => !is_null($this->scolarFee)
        ]);

        // Forcer le re-render du composant
        $this->dispatch('$refresh');
    }

    /**
     * Convertir le label du mois en numéro
     */
    private function convertMonthLabelToNumber(string $monthLabel): string
    {
        $months = [
            'JANVIER' => '01',
            'FEVRIER' => '02',
            'MARS' => '03',
            'AVRIL' => '04',
            'MAI' => '05',
            'JUIN' => '06',
            'JUILLET' => '07',
            'AOUT' => '08',
            'SEPTEMBRE' => '09',
            'OCTOBRE' => '10',
            'NOVEMBRE' => '11',
            'DECEMBRE' => '12',
        ];

        return $months[strtoupper($monthLabel)] ?? '01';
    }

    /**
     * Enregistrer le paiement
     */
    public function save(): void
    {
        if (!$this->registration) {
            $this->dispatch('error', ['message' => 'Veuillez sélectionner un élève']);
            return;
        }

        if (!$this->selectedCategoryFeeId) {
            $this->dispatch('error', ['message' => 'Veuillez sélectionner une catégorie de frais']);
            return;
        }

        // Définir category_fee_id pour la validation
        $this->form->category_fee_id = $this->selectedCategoryFeeId;

        $this->validate();

        if ($this->isEditMode && $this->paymentId) {
            // Mode modification
            $action = app(UpdatePaymentAction::class);
            $result = $action->execute(
                $this->paymentId,
                $this->selectedCategoryFeeId,
                $this->form->month,
                [
                    'created_at' => $this->form->created_at,
                    'is_paid' => $this->isPaid,
                ]
            );

            if (!$result['success']) {
                $this->dispatch('error', ['message' => $result['message'] ?? 'Erreur lors de la modification']);
            } else {
                $this->dispatch('added', ['message' => $result['message']]);
                $this->dispatch('paymentUpdated');
                $this->dispatch('refreshStudentHistory');
            }
        } else {
            // Mode création
            $action = app(CreatePaymentAction::class);
            $result = $action->execute(
                $this->registration->id,
                $this->selectedCategoryFeeId,
                $this->form->month,
                [
                    'created_at' => $this->form->created_at,
                    'is_paid' => $this->isPaid,
                ]
            );

            if (!$result['success']) {
                $this->dispatch('error', ['message' => $result['message'] ?? 'Erreur lors de la création']);
            } else {
                $this->dispatch('added', ['message' => $result]);
                $this->dispatch('paymentCreated');
                $this->dispatch('refreshStudentHistory');
            }
        }

        $this->resetAfterSave();
    }

    /**
     * Réinitialiser après enregistrement
     */
    private function resetAfterSave(): void
    {
        $this->isEditMode = false;
        $this->paymentId = null;
        $this->registrationId = null;
        $this->registration = null;
        $this->initFormFields();
        $this->selectedCategoryFeeId = null;
        $this->scolarFee = null;
        $this->isPaid = false;
    }

    /**
     * Réinitialiser le formulaire
     */
    public function resetForm(): void
    {
        $this->registrationId = null;
        $this->registration = null;
        $this->resetAfterSave();
    }

    /**
     * Annuler et réinitialiser
     */
    public function cancel(): void
    {
        $this->resetAfterSave();
    }



    public function render()
    {
        return view('livewire.application.payment.payment-form-component');
    }
}
