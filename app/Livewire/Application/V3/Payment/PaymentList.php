<?php

namespace App\Livewire\Application\V3\Payment;

use App\Actions\Payment\DeletePaymentAction;
use App\Models\CategoryFee;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    // Constantes
    private const FILTER_ALL = 'all';
    private const FILTER_PAID = 'paid';
    private const FILTER_UNPAID = 'unpaid';
    private const MAX_PAYMENTS_DISPLAYED = 50;

    // Filtres
    public $filterStatus = self::FILTER_ALL;
    public $filterCategoryFeeId = '';
    public $filterDate = '';
    public $selectedRegistrationId = null;

    // Liste des paiements
    public $payments = [];
    public $totalsByCurrency = [];

    // Action
    private DeletePaymentAction $deletePaymentAction;

    public function boot(DeletePaymentAction $deletePaymentAction): void
    {
        $this->deletePaymentAction = $deletePaymentAction;
    }

    public function mount(): void
    {
        // Initialiser la date avec la date du jour
        $this->filterDate = date('Y-m-d');
        
        // Initialiser la catégorie avec "Minerval" par défaut
        $minervalCategory = CategoryFee::whereRaw('LOWER(name) LIKE ?', ['%minerval%'])->first();
        if ($minervalCategory) {
            $this->filterCategoryFeeId = $minervalCategory->id;
        } else {
            // Si Minerval n'existe pas, prendre la première catégorie disponible
            $firstCategory = CategoryFee::orderBy('name')->first();
            if ($firstCategory) {
                $this->filterCategoryFeeId = $firstCategory->id;
            }
        }
        
        $this->loadPayments();
    }

    #[On('paymentSaved')]
    public function handlePaymentSaved(): void
    {
        $this->loadPayments();
    }

    #[On('studentSelected')]
    public function handleStudentSelected($registrationId): void
    {
        $this->selectedRegistrationId = $registrationId;
        $this->loadPayments();
    }

    #[On('studentReset')]
    public function handleStudentReset(): void
    {
        $this->selectedRegistrationId = null;
        $this->loadPayments();
    }

    /**
     * Charger la liste des paiements
     */
    public function loadPayments(): void
    {
        // Vérification comme dans DailyPaymentList
        if (!$this->filterCategoryFeeId) {
            $this->resetPaymentData();
            return;
        }

        $query = Payment::with([
            'registration.student',
            'registration.classRoom.option.section',
            'scolarFee.categoryFee',
            'rate',
            'user'
        ])->latest();

        $this->applyStatusFilter($query);
        $this->applyStudentFilter($query);
        $this->applyCategoryFilter($query);
        $this->applyDateFilter($query);

        $this->payments = $query->limit(self::MAX_PAYMENTS_DISPLAYED)->get();
        
        // Debug: Log le nombre de paiements trouvés
        Log::info('PaymentList - Payments loaded', [
            'count' => $this->payments->count(),
            'categoryFeeId' => $this->filterCategoryFeeId,
            'filterDate' => $this->filterDate,
            'filterStatus' => $this->filterStatus,
        ]);
        
        $this->calculateTotals();
    }

    /**
     * Réinitialiser les données de paiement
     */
    private function resetPaymentData(): void
    {
        $this->payments = [];
        $this->totalsByCurrency = [];
    }

    /**
     * Appliquer le filtre de statut
     */
    private function applyStatusFilter($query): void
    {
        match ($this->filterStatus) {
            self::FILTER_PAID => $query->where('is_paid', true),
            self::FILTER_UNPAID => $query->where('is_paid', false),
            default => null
        };
    }

    /**
     * Appliquer le filtre par élève
     */
    private function applyStudentFilter($query): void
    {
        if ($this->selectedRegistrationId) {
            $query->where('registration_id', $this->selectedRegistrationId);
        }
    }

    /**
     * Appliquer le filtre par catégorie
     */
    private function applyCategoryFilter($query): void
    {
        if ($this->filterCategoryFeeId) {
            $query->whereHas('scolarFee', function ($q) {
                $q->where('category_fee_id', $this->filterCategoryFeeId);
            });
        }
    }

    /**
     * Appliquer le filtre par date
     */
    private function applyDateFilter($query): void
    {
        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }
    }

    /**
     * Calculer les totaux par devise pour les paiements validés
     */
    private function calculateTotals(): void
    {
        $this->totalsByCurrency = [];

        foreach ($this->payments as $payment) {
            // Ne compter que les paiements validés
            if ($payment->is_paid) {
                $currency = $payment->rate->currency_code ?? 'CDF';
                $amount = $payment->scolarFee->amount ?? 0;

                if (!isset($this->totalsByCurrency[$currency])) {
                    $this->totalsByCurrency[$currency] = 0;
                }

                $this->totalsByCurrency[$currency] += $amount;
            }
        }
    }

    /**
     * Éditer un paiement
     */
    public function editPayment(int $paymentId): void
    {
        $this->dispatch('editPayment', paymentId: $paymentId);
    }

    /**
     * Valider un paiement
     */
    public function validatePayment(int $paymentId): void
    {
        try {
            $payment = Payment::findOrFail($paymentId);

            if ($payment->is_paid) {
                $this->dispatch('error', ['message' => 'Ce paiement est déjà validé']);
                return;
            }

            $payment->update(['is_paid' => true]);
            $this->dispatch('added', ['message' => 'Paiement validé avec succès']);
            $this->loadPayments();

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur lors de la validation du paiement']);
        }
    }

    /**
     * Supprimer un paiement
     */
    public function deletePayment(int $paymentId): void
    {
        try {
            $payment = Payment::findOrFail($paymentId);

            if ($payment->is_paid) {
                $this->dispatch('error', ['message' => 'Impossible de supprimer un paiement validé']);
                return;
            }

            $result = $this->deletePaymentAction->execute($payment);

            if (!$result['success']) {
                $this->dispatch('error', ['message' => $result['message'] ?? 'Erreur lors de la suppression']);
                throw new \Exception($result['message'] ?? 'Erreur lors de la suppression');
            }

            $this->dispatch('added', ['message' => 'Paiement supprimé avec succès']);
            $this->loadPayments();

        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => 'Erreur lors de la suppression du paiement']);
        }
    }

    /**
     * Changer le filtre de statut
     */
    public function updatedFilterStatus(): void
    {
        $this->loadPayments();
    }

    /**
     * Changer le filtre de catégorie
     */
    public function updatedFilterCategoryFeeId(): void
    {
        $this->loadPayments();
    }

    /**
     * Changer le filtre de date
     */
    public function updatedFilterDate(): void
    {
        $this->loadPayments();
    }

    /**
     * Récupérer les catégories de frais
     */
    public function getCategoryFeesProperty()
    {
        return CategoryFee::orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.application.v3.payment.payment-list');
    }
}
