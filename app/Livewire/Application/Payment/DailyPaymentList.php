<?php

namespace App\Livewire\Application\Payment;

use App\Actions\Payment\DeletePaymentAction;
use App\Actions\Payment\TogglePaymentStatusAction;
use App\DTOs\Payment\PaymentFilterDTO;
use App\Services\CategoryFee\CategoryFeeService;
use App\Services\Contracts\PaymentServiceInterface;
use Livewire\Component;
use Livewire\WithPagination;

class DailyPaymentList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtres
    public $searchPayment = '';
    public $filterDate;
    public $filterCategoryFeeId;
    public $dateRange = null;
    public $dateDebut = null;
    public $dateFin = null;

    // Filtres académiques
    public $sectionId = null;
    public $optionId = null;
    public $sections = [];
    public $options = [];
    public $classRooms = [];

    // Résultat des paiements
    public $payments = [];
    public $totalCount = 0;
    public $totalsByCurrency = [];
    public $currentPage = 1;
    public $lastPage = 1;
    public $hasMorePages = false;
    public $statistics = [
        'paid_count' => 0,
        'unpaid_count' => 0,
        'payment_rate' => 0,
    ];

    // Données disponibles
    public $categoryFees = [];

    // Historique
    public $showHistoryModal = false;
    public $historyData = [];

    // Services
    private PaymentServiceInterface $paymentService;
    private CategoryFeeService $categoryFeeService;

    protected $listeners = [
        'refreshPaymentList' => 'loadPayments',
        'paymentCreated' => 'loadPayments',
        'paymentUpdated' => 'loadPayments',
        'paymentDeleted' => 'loadPayments',
        'refreshDailyPaymentList' => 'loadPayments',
    ];

    public function boot(
        PaymentServiceInterface $paymentService,
        CategoryFeeService $categoryFeeService
    ): void {
        $this->paymentService = $paymentService;
        $this->categoryFeeService = $categoryFeeService;
    }

    public function mount(): void
    {
        $this->loadCategoryFees();

        // Sélectionner Minerval par défaut
        $this->filterCategoryFeeId = $this->categoryFeeService->findMinervalCategoryId($this->categoryFees);
        $this->filterDate = $this->filterDate ?? date('Y-m-d');

        $this->loadPayments();
    }

    /**
     * Charger les catégories de frais
     */
    public function loadCategoryFees(): void
    {
        $this->categoryFees = $this->categoryFeeService->getAllCategoryFees();
    }

    /**
     * Charger les paiements filtrés
     */
    public function loadPayments(): void
    {
        if (!$this->filterCategoryFeeId) {
            $this->resetPaymentData();
            return;
        }

        $filterDTO = PaymentFilterDTO::fromArray([
            'date' => $this->filterDate,
            'categoryFeeId' => $this->filterCategoryFeeId,
            'search' => $this->searchPayment,
        ]);

        $result = $this->paymentService->getFilteredPayments($filterDTO, 10, $this->getPage());

        // Décomposer le DTO en propriétés simples
        $this->payments = $result->payments->items();
        $this->totalCount = $result->totalCount;
        $this->totalsByCurrency = $result->totalsByCurrency;
        $this->currentPage = $result->payments->currentPage();
        $this->lastPage = $result->payments->lastPage();
        $this->hasMorePages = $result->payments->hasMorePages();

        // Calculer les statistiques
        $this->calculateStatistics();
    }

    /**
     * Réinitialiser les données de paiement
     */
    private function resetPaymentData(): void
    {
        $this->payments = [];
        $this->totalCount = 0;
        $this->totalsByCurrency = [];
        $this->currentPage = 1;
        $this->lastPage = 1;
        $this->hasMorePages = false;
        $this->statistics = [
            'paid_count' => 0,
            'unpaid_count' => 0,
            'payment_rate' => 0,
        ];
    }

    /**
     * Calculer les statistiques de paiement
     */
    private function calculateStatistics(): void
    {
        $paidCount = collect($this->payments)->where('is_paid', true)->count();
        $unpaidCount = collect($this->payments)->where('is_paid', false)->count();
        $total = $paidCount + $unpaidCount;

        $this->statistics = [
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
            'payment_rate' => $total > 0 ? ($paidCount / $total) * 100 : 0,
        ];
    }

    /**
     * Computed property pour la pagination
     */
    public function getPaymentsPaginatedProperty()
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $this->payments,
            $this->totalCount,
            10,
            $this->currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }



    /**
     * Mise à jour du filtre de date
     */
    public function updatedFilterDate(): void
    {
        $this->resetPage();
        $this->loadPayments();
    }

    /**
     * Mise à jour du filtre de catégorie
     */
    public function updatedFilterCategoryFeeId(): void
    {
        $this->resetPage();
        $this->loadPayments();
    }

    /**
     * Mise à jour de la recherche
     */
    public function updatedSearchPayment(): void
    {
        $this->resetPage();
        $this->loadPayments();
    }

    /**
     * Mise à jour de la page
     */
    public function updatedPage(): void
    {
        $this->loadPayments();
    }

    /**
     * Modifier un paiement
     */
    public function editPayment(int $paymentId): void
    {
        $payment = \App\Models\Payment::with(['registration.student', 'registration.classRoom', 'scolarFee.categoryFee'])
            ->find($paymentId);

        if (!$payment) {
            $this->dispatch('error', ['message' => 'Paiement non trouvé']);
            return;
        }

        // Log pour debug
        \Log::info('Edit payment clicked', [
            'paymentId' => $payment->id,
            'registrationId' => $payment->registration_id,
            'categoryFeeId' => $payment->scolarFee->category_fee_id ?? null
        ]);

        // Émettre l'événement vers tous les composants Livewire sur la page
        $this->dispatch(
            'editPayment',
            id: $payment->id,
            registration_id: $payment->registration_id,
            category_fee_id: $payment->scolarFee->category_fee_id ?? null,
            month: $payment->month,
            created_at: $payment->created_at->format('Y-m-d'),
            is_paid: $payment->is_paid
        );
    }

    /**
     * Basculer le statut de paiement
     */
    public function togglePaymentStatus(int $paymentId): void
    {
        $action = app(TogglePaymentStatusAction::class);
        $result = $action->execute($paymentId);

        if (!$result['success']) {
            $this->dispatch('error', ['message' => $result['message']]);
            return;
        }

        $this->loadPayments();
        $this->dispatch('added', ['message' => $result['message']]);
        $this->dispatch('paymentUpdated');
    }

    /**
     * Envoyer SMS
     */
    public function sendSms(int $paymentId): void
    {
        try {
            $payment = \App\Models\Payment::find($paymentId);

            if (!$payment) {
                $this->dispatch('error', ['message' => 'Paiement non trouvé']);
                return;
            }

            // TODO: Implémenter l'envoi de SMS
            $this->dispatch('added', ['message' => 'SMS envoyé avec succès']);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur lors de l\'envoi du SMS: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirmer la suppression
     */
    public function confirmDelete(int $paymentId): void
    {
        $payment = \App\Models\Payment::with(['registration.student', 'scolarFee.categoryFee'])->find($paymentId);

        if (!$payment) {
            $this->dispatch('error', ['message' => 'Paiement non trouvé']);
            return;
        }

        // Empêcher la suppression si le paiement est validé
        if ($payment->is_paid) {
            $this->dispatch('error', ['message' => 'Impossible de supprimer un paiement déjà validé. Veuillez d\'abord le marquer comme non payé.']);
            return;
        }

        $this->dispatch('delete-payment-dialog', [
            'paymentId' => $payment->id,
            'studentName' => $payment->registration->student->name ?? 'Inconnu',
            'amount' => $payment->scolarFee->amount ?? 0,
            'currency' => $payment->scolarFee->categoryFee->currency ?? ''
        ]);
    }

    /**
     * Supprimer un paiement
     */
    public function deletePayment(int $paymentId): void
    {
        $action = app(DeletePaymentAction::class);
        $result = $action->execute($paymentId);

        if (!$result['success']) {
            $this->dispatch('delete-payment-failed', ['message' => $result['message']]);
            return;
        }

        $this->dispatch('payment-deleted', ['message' => $result['message']]);
        $this->dispatch('paymentDeleted');
        $this->loadPayments();
    }

    /**
     * Afficher l'historique du paiement
     */
    public function showPaymentHistory(int $paymentId): void
    {
        $payment = \App\Models\Payment::find($paymentId);

        if (!$payment) {
            $this->dispatch('error', ['message' => 'Paiement non trouvé']);
            return;
        }

        // Placeholder pour l'historique - à remplacer par la vraie logique
        $this->historyData = [
            [
                'action' => 'Paiement créé',
                'date' => $payment->created_at->format('d/m/Y H:i'),
                'details' => 'Montant: ' . number_format($payment->scolarFee->amount ?? 0, 0, ',', ' ') . ' ' . ($payment->scolarFee->categoryFee->currency ?? '')
            ],
            [
                'action' => 'Statut mis à jour',
                'date' => $payment->updated_at->format('d/m/Y H:i'),
                'details' => $payment->is_paid ? 'Marqué comme payé' : 'Marqué comme en attente'
            ]
        ];

        $this->showHistoryModal = true;
    }

    /**
     * Fermer le modal d'historique
     */
    public function closeHistoryModal(): void
    {
        $this->showHistoryModal = false;
        $this->historyData = [];
    }

    public function render()
    {
        return view('livewire.application.payment.daily-payment-list', [
            'paymentsPaginated' => $this->getPaymentsPaginatedProperty()
        ]);
    }
}
