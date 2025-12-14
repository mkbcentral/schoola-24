<?php

namespace App\Livewire\Application\Payment\List;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Services\Contracts\PaymentServiceInterface;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Exemple de composant Livewire utilisant PaymentService
 */
class PaymentListWithService extends Component
{
    use WithPagination;

    // Filtres publics (bindés avec les inputs)
    public ?string $date = null;

    public ?string $month = null;

    public ?string $period = null;

    public ?int $categoryFeeId = null;

    public ?int $feeId = null;

    public ?int $sectionId = null;

    public ?int $optionId = null;

    public ?int $classRoomId = null;

    public ?bool $isPaid = null;

    public ?string $currency = null;

    public ?string $search = '';

    public int $perPage = 15;

    // Service injecté
    private PaymentServiceInterface $paymentService;

    /**
     * Injection du service via boot()
     */
    public function boot(PaymentServiceInterface $paymentService): void
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Réinitialiser les filtres
     */
    public function resetFilters(): void
    {
        $this->reset([
            'date',
            'month',
            'period',
            'categoryFeeId',
            'feeId',
            'sectionId',
            'optionId',
            'classRoomId',
            'isPaid',
            'currency',
            'search',
        ]);

        $this->resetPage();
    }

    /**
     * Changer le nombre d'éléments par page
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Render du composant
     */
    public function render()
    {
        // Créer le DTO de filtres
        $filters = PaymentFilterDTO::fromArray([
            'date' => $this->date,
            'month' => $this->month,
            'period' => $this->period,
            'categoryFeeId' => $this->categoryFeeId,
            'feeId' => $this->feeId,
            'sectionId' => $this->sectionId,
            'optionId' => $this->optionId,
            'classRoomId' => $this->classRoomId,
            'isPaid' => $this->isPaid,
            'currency' => $this->currency,
            'search' => $this->search,
        ]);

        // Récupérer les résultats via le service
        $result = $this->paymentService->getFilteredPayments($filters, $this->perPage);

        return view('livewire.application.payment.list.payment-list-with-service', [
            'payments' => $result->payments,
            'totalCount' => $result->totalCount,
            'totalsByCurrency' => $result->totalsByCurrency,
            'statistics' => $result->statistics,
        ]);
    }
}
