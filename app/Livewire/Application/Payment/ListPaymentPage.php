<?php

namespace App\Livewire\Application\Payment;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Exemple d'utilisation du Repository Pattern dans un composant Livewire
 *
 * AVANT : Le composant faisait des requêtes directes au modèle Payment
 * APRÈS : Le composant utilise le repository qui gère l'optimisation
 */
class ListPaymentPage extends Component
{
    use WithPagination;

    public string $search = '';

    public ?string $month = null;

    public ?int $categoryFeeId = null;

    public ?bool $isPaid = null;

    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'month' => ['except' => null],
        'categoryFeeId' => ['except' => null],
        'isPaid' => ['except' => null],
    ];

    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    /**
     * Réinitialiser la pagination lors de la recherche
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Réinitialiser la pagination lors du changement de filtre
     */
    public function updatedMonth(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFeeId(): void
    {
        $this->resetPage();
    }

    public function updatedIsPaid(): void
    {
        $this->resetPage();
    }

    /**
     * Récupérer les paiements avec filtres
     * Le repository gère automatiquement l'eager loading et l'optimisation
     */
    public function render()
    {
        $filters = [
            'key_to_search' => $this->search,
            'month' => $this->month,
            'categoryFeeId' => $this->categoryFeeId,
            'isPaid' => $this->isPaid,
        ];

        // Le repository gère tout : eager loading, optimisation, cache
        $payments = $this->paymentRepository->getAllWithFilters($filters, $this->perPage);

        // Récupérer les statistiques (avec cache automatique)
        $statistics = $this->paymentRepository->getPaymentStatistics($filters);

        return view('livewire.application.payment.list-payment-page', [
            'payments' => $payments,
            'statistics' => $statistics,
        ]);
    }
}
