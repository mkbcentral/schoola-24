<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Helpers\SmsNotificationHelper;
use App\Domain\Utils\AppMessage;
use App\DTOs\Payment\PaymentFilterDTO;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Services\CategoryFee\CategoryFeeService;
use App\Services\Contracts\PaymentServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListPaymentByDatePage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Services
    private PaymentServiceInterface $paymentService;
    private CategoryFeeService $categoryFeeService;

    protected $listeners = [
        'refreshPaymentList' => 'loadPayments',
        'paymentCreated' => 'loadPayments',
        'paymentUpdated' => 'loadPayments',
        'paymentDeleted' => 'loadPayments',
    ];

    public function boot(
        PaymentServiceInterface $paymentService,
        CategoryFeeService $categoryFeeService
    ): void {
        $this->paymentService = $paymentService;
        $this->categoryFeeService = $categoryFeeService;
    }

    // Filtres
    public ?string $date_filter = '';
    public int $category_fee_filter = 0;
    public int $per_page = 100;

    #[Url(as: 'q')]
    public $q = '';

    // Résultats
    public $payments = [];
    public $totalCount = 0;
    public $totalsByCurrency = [];
    public $currentPage = 1;
    public $lastPage = 1;
    public $hasMorePages = false;

    // Données disponibles
    public $categoryFees = [];
    public ?CategoryFee $categoryFeeSelected = null;

    public function mount(): void
    {
        $this->date_filter = date('Y-m-d');

        // Charger les catégories de frais
        $this->categoryFees = $this->categoryFeeService->getAllCategoryFees();

        // Sélectionner Minerval par défaut
        $this->category_fee_filter = $this->categoryFeeService->findMinervalCategoryId(collect($this->categoryFees));

        if ($this->category_fee_filter) {
            $this->categoryFeeSelected = CategoryFee::find($this->category_fee_filter);
        }

        $this->loadPayments();
    }

    public function updatedCategoryFeeFilter($val): void
    {
        $this->categoryFeeSelected = CategoryFee::findOrFail($val);
        $this->loadPayments();
    }

    public function updatedDateFilter(): void
    {
        $this->loadPayments();
    }

    public function updatedQ(): void
    {
        $this->loadPayments();
    }

    /**
     * Charger les paiements filtrés
     */
    public function loadPayments(): void
    {
        if (!$this->category_fee_filter) {
            $this->resetPaymentData();
            return;
        }

        $filterDTO = PaymentFilterDTO::fromArray([
            'date' => $this->date_filter,
            'categoryFeeId' => $this->category_fee_filter,
            'search' => $this->q,
            'userId' => Auth::id(),
        ]);

        $result = $this->paymentService->getFilteredPayments($filterDTO, $this->per_page, $this->getPage());

        // Décomposer le DTO en propriétés simples
        $this->payments = $result->payments->items();
        $this->totalCount = $result->totalCount;
        $this->totalsByCurrency = $result->totalsByCurrency;
        $this->currentPage = $result->payments->currentPage();
        $this->lastPage = $result->payments->lastPage();
        $this->hasMorePages = $result->payments->hasMorePages();
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
    }

    public function edit(Payment $payment): void
    {
        $this->dispatch('paymentData', $payment);
    }

    public function delete(?Payment $payment): void
    {
        try {
            if (! $payment->is_paid) {
                $payment->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => 'Action impossible, car le paiement est déjà validé']);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function makeIsPaid(?Payment $payment): void
    {
        try {
            if ($payment->is_paid) {
                $payment->is_paid = false;
            } else {
                $payment->is_paid = true;
            }
            $payment->update();
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function sendSms(Payment $payment): void
    {

        try {
            if ($payment->smsPayment == null) {
                // $phone = '+243898337969';
                $phone = $payment->registration->student->responsibleStudent->phone;
                $phone = str_replace([' ', '(', ')', '-'], '', $phone);
                $message = 'C.S AQUILA, Cher parent, votre enfant '
                    . $payment->registration->student->name . ' est en ordre avec le frais '
                    . $payment->scolarFee->name . ' Montant : ' . $payment->scolarFee->amount . ' '
                    . $payment->scolarFee->categoryFee->currency . ' '
                    . format_fr_month_name($payment->month) . ' , Merci pour votre confiance.';
                SmsNotificationHelper::sendOrangeSMS($phone, $message);
                $payment->smsPayment()->create([
                    'receiver' => $phone,
                    'message' => $message,
                ]);
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            } else {
                $this->dispatch('updated', ['message' => 'Message déjà envoyé']);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    /**
     * Obtenir le total pour la devise sélectionnée
     */
    public function getTotalPaymentsProperty(): float
    {
        if (!$this->categoryFeeSelected) {
            return 0;
        }

        $currency = $this->categoryFeeSelected->currency;
        return $this->totalsByCurrency[$currency] ?? 0;
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.payment.list.list-payment-by-date-page', [
            'total_payments' => $this->totalPayments,
        ]);
    }
}
