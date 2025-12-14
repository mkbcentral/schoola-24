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
use Livewire\Component;
use Livewire\WithPagination;

class ListReportPaymentPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Services
    private PaymentServiceInterface $paymentService;
    private CategoryFeeService $categoryFeeService;

    public function boot(
        PaymentServiceInterface $paymentService,
        CategoryFeeService $categoryFeeService
    ): void {
        $this->paymentService = $paymentService;
        $this->categoryFeeService = $categoryFeeService;
    }

    protected $listeners = [
        'selectedCategoryFee' => 'getSelectedCategoryFee',
        'refreshPaymentList' => 'loadPayments',
        'paymentCreated' => 'loadPayments',
        'paymentUpdated' => 'loadPayments',
        'paymentDeleted' => 'loadPayments',
    ];

    // Filtres
    public int $selectedCategoryFeeId = 0;
    public ?CategoryFee $categoryFeeSelected;
    public ?string $date_filter = '';
    public string $month_filter = '';
    public int $option_filter = 0;
    public ?int $section_filter = 0;
    public ?int $class_room_filter = 0;
    public ?int $scolary_fee_filter = 0;
    public ?int $selectedSection = 0;
    public ?int $selectedOption = 0;
    public ?int $selectedClassRoom = 0;
    public ?int $per_page = 1000;
    public bool $isByDate = true;

    // Résultats (totaux uniquement, la pagination est gérée dans render())
    public $totalCount = 0;
    public $totalsByCurrency = [];

    /**
     * Recuprer le categorie de frais selectionné
     */
    public function getSelectedCategoryFee(int $index): void
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::find($index);
    }

    public function updatedMonthFilter(): void
    {
        $this->date_filter = '';
        $this->isByDate = false;
        $this->loadPayments();
    }

    public function updatedDateFilter(): void
    {
        $this->month_filter = '';
        $this->isByDate = true;
        $this->loadPayments();
    }

    public function updatedSectionFilter(mixed $val): void
    {
        $this->selectedSection = $val;
        $this->loadPayments();
    }

    public function updatedOptionFilter(mixed $val): void
    {
        $this->selectedOption = $val;
        $this->loadPayments();
    }

    public function updatedClassRoomFilter($val): void
    {
        $this->selectedClassRoom = $val;
        $this->loadPayments();
    }

    public function updatedScolaryFeeFilter(): void
    {
        $this->loadPayments();
    }

    public function mount(int $categoryFeeId): void
    {
        $this->selectedCategoryFeeId = $categoryFeeId;
        $this->categoryFeeSelected = CategoryFee::find($categoryFeeId);
        $this->date_filter = date('Y-m-d');
        $this->loadPayments();
    }

    /**
     * Charger les paiements filtrés avec tous les paramètres disponibles
     */
    public function loadPayments(): void
    {
        if (!$this->selectedCategoryFeeId) {
            $this->resetPaymentData();
            return;
        }

        $filterDTO = PaymentFilterDTO::fromArray([
            'date' => $this->date_filter ?: null,
            'month' => $this->month_filter ?: null,
            'categoryFeeId' => $this->selectedCategoryFeeId,
            'feeId' => $this->scolary_fee_filter ?: null,
            'sectionId' => $this->section_filter ?: null,
            'optionId' => $this->selectedOption ?: null,
            'classRoomId' => $this->class_room_filter ?: null,
            'isPaid' => true,
            'userId' => Auth::id(),
        ]);

        $result = $this->paymentService->getFilteredPayments($filterDTO, $this->per_page, $this->getPage());

        // Stocker uniquement les totaux (pas l'objet paginé pour éviter les problèmes de sérialisation)
        $this->totalCount = $result->totalCount;
        $this->totalsByCurrency = $result->totalsByCurrency;
    }

    /**
     * Réinitialiser les données de paiement
     */
    private function resetPaymentData(): void
    {
        $this->totalCount = 0;
        $this->totalsByCurrency = [];
    }

    public function sendSMS(Payment $payment): void
    {
        try {
            $phone = '+243971330007'; // $payment->registration->student->responsibleStudent->phone;
            SmsNotificationHelper::sendOrangeSMS($phone, 'Test sms');
            $this->dispatch('updated', ['message' => AppMessage::SMS_SENT]);
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
        // Générer les paiements à chaque render pour éviter les problèmes de sérialisation Livewire
        $payments = collect([]);

        if ($this->selectedCategoryFeeId) {
            $filterDTO = PaymentFilterDTO::fromArray([
                'date' => $this->date_filter ?: null,
                'month' => $this->month_filter ?: null,
                'categoryFeeId' => $this->selectedCategoryFeeId,
                'feeId' => $this->scolary_fee_filter ?: null,
                'sectionId' => $this->section_filter ?: null,
                'optionId' => $this->selectedOption ?: null,
                'classRoomId' => $this->class_room_filter ?: null,
                'isPaid' => true,
                'userId' => Auth::id(),
            ]);

            $result = $this->paymentService->getFilteredPayments($filterDTO, $this->per_page, $this->getPage());
            $payments = $result->payments;
        }

        return view('livewire.application.payment.list.list-report-payment-page', [
            'payments' => $payments,
            'total_payments' => $this->totalPayments,
        ]);
    }
}
