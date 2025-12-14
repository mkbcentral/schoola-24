<?php

namespace App\Livewire\Application\Payment;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Services\Contracts\PaymentServiceInterface;
use App\Services\Payment\PaymentListReportService;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Composant Livewire pour afficher la liste des paiements avec filtres
 */
class PaymentListPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filtres
    public ?string $date = null;
    public ?string $month = null;
    public ?string $dateDebut = null;
    public ?string $dateFin = null;
    public ?string $dateRange = null;
    public ?int $categoryFeeId = null;
    public ?int $feeId = null;
    public ?int $sectionId = null;
    public ?int $optionId = null;
    public ?int $classRoomId = null;
    public ?bool $isPaid = null;
    public ?int $userId = null;
    public ?string $search = null;

    // Configuration
    public int $perPage = 15;
    public bool $showFilters = true;

    // Services injectés
    private PaymentServiceInterface $paymentService;
    private PaymentListReportService $reportService;

    /**
     * Injection des services via boot (compatible Livewire 3)
     */
    public function boot(
        PaymentServiceInterface $paymentService,
        PaymentListReportService $reportService
    ): void {
        $this->paymentService = $paymentService;
        $this->reportService = $reportService;
    }

    /**
     * Initialisation du composant
     */
    public function mount(): void
    {
        $this->categoryFeeId = $this->getDefaultCategoryFeeId();
    }

    /**
     * Obtenir l'ID de la catégorie de frais par défaut
     */
    private function getDefaultCategoryFeeId(): ?int
    {
        $categoryFees = $this->getCategoryFees();

        // Sélectionner par défaut la catégorie "MINERVAL"
        $minerval = $categoryFees->first(fn($category) => stripos($category->name, 'MINERVAL') !== false);

        return $minerval?->id ?? $categoryFees->first()?->id;
    }

    /**
     * Réinitialiser les filtres (sauf categoryFeeId)
     */
    public function resetFilters(): void
    {
        $this->reset([
            'date',
            'month',
            'dateDebut',
            'dateFin',
            'dateRange',
            'feeId',
            'sectionId',
            'optionId',
            'classRoomId',
            'isPaid',
            'userId',
            'search',
        ]);
        $this->resetPage();
    }

    /**
     * Hook appelé quand categoryFeeId change - réinitialise tous les autres filtres
     */
    public function updatedCategoryFeeId(): void
    {
        $this->resetFilters();
    }

    /**
     * Activer/désactiver l'affichage des filtres
     */
    public function toggleFilters(): void
    {
        $this->showFilters = ! $this->showFilters;
    }

    /**
     * Générer le rapport PDF des paiements avec les filtres actuels
     */
    public function generatePdf()
    {
        if (!$this->categoryFeeId) {
            session()->flash('error', 'Veuillez sélectionner une catégorie de frais avant de générer le rapport.');
            return;
        }

        // Stocker les filtres en session pour la route PDF
        session()->put('payment_pdf_filters', [
            'date' => $this->date,
            'month' => $this->month,
            'dateDebut' => $this->dateDebut,
            'dateFin' => $this->dateFin,
            'dateRange' => $this->dateRange,
            'categoryFeeId' => $this->categoryFeeId,
            'feeId' => $this->feeId,
            'sectionId' => $this->sectionId,
            'optionId' => $this->optionId,
            'classRoomId' => $this->classRoomId,
            'isPaid' => $this->isPaid,
            'userId' => $this->userId,
            'search' => $this->search,
        ]);

        // Rediriger vers une route dédiée pour générer le PDF
        return redirect()->route('payments.pdf');
    }

    /**
     * Hook Livewire : appelé automatiquement quand un filtre change
     */
    public function updated($propertyName): void
    {
        // Si c'est categoryFeeId, updatedCategoryFeeId() sera appelé automatiquement
        if ($propertyName === 'categoryFeeId') {
            return;
        }

        // Gestion des filtres de date mutuellement exclusifs
        $dateFilterConflicts = [
            'date' => ['month', 'dateRange', 'dateDebut', 'dateFin'],
            'month' => ['date', 'dateRange', 'dateDebut', 'dateFin'],
            'dateRange' => ['date', 'month', 'dateDebut', 'dateFin'],
            'dateDebut' => ['date', 'month', 'dateRange'],
            'dateFin' => ['date', 'month', 'dateRange'],
        ];

        // Réinitialiser les filtres de date conflictuels
        if (isset($dateFilterConflicts[$propertyName]) && $this->{$propertyName}) {
            foreach ($dateFilterConflicts[$propertyName] as $property) {
                $this->{$property} = null;
            }
        }

        // Liste des filtres en cascade qui doivent réinitialiser leurs dépendances
        $cascadeResets = [
            'sectionId' => ['optionId', 'classRoomId'],
            'optionId' => ['classRoomId'],
        ];

        // Réinitialiser les filtres dépendants si nécessaire
        if (isset($cascadeResets[$propertyName])) {
            foreach ($cascadeResets[$propertyName] as $property) {
                $this->{$property} = null;
            }
        }

        // Toujours réinitialiser la pagination quand un filtre change
        $this->resetPage();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $result = $this->getPaymentResults();

        return view('livewire.application.payment.payment-list-page', [
            'payments' => $result->payments,
            'totalCount' => $result->totalCount,
            'totalsByCurrency' => $result->totalsByCurrency,
            'statistics' => $result->statistics,
            'categoryFees' => $this->getCategoryFees(),
            'sections' => $this->getSections(),
            'options' => $this->getOptions(),
            'classRooms' => $this->getClassRooms(),
        ]);
    }

    /**
     * Construire le DTO des filtres
     */
    private function buildFilterDTO(): PaymentFilterDTO
    {
        $period = ($this->dateDebut && $this->dateFin)
            ? "{$this->dateDebut}:{$this->dateFin}"
            : null;

        return PaymentFilterDTO::fromArray([
            'date' => $this->date,
            'month' => $this->month,
            'period' => $period,
            'dateRange' => $this->dateRange,
            'categoryFeeId' => $this->categoryFeeId,
            'feeId' => $this->feeId,
            'sectionId' => $this->sectionId,
            'optionId' => $this->optionId,
            'classRoomId' => $this->classRoomId,
            'isPaid' => $this->isPaid,
            'userId' => $this->userId,
            'search' => $this->search,
        ]);
    }

    /**
     * Récupérer les résultats des paiements
     */
    private function getPaymentResults()
    {
        if (!$this->categoryFeeId) {
            return $this->getEmptyResults();
        }

        $filterDTO = $this->buildFilterDTO();

        return $this->paymentService->getFilteredPayments($filterDTO, $this->perPage, $this->getPage());
    }

    /**
     * Retourner des résultats vides
     */
    private function getEmptyResults()
    {
        return new \App\DTOs\Payment\PaymentResultDTO(
            payments: new LengthAwarePaginator([], 0, $this->perPage),
            totalCount: 0,
            totalsByCurrency: [],
            statistics: [
                'paid_count' => 0,
                'unpaid_count' => 0,
                'payment_rate' => 0,
            ]
        );
    }

    /**
     * Récupérer les catégories de frais
     */
    #[Computed]
    private function getCategoryFees()
    {
        return CategoryFee::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->orderBy('name')
            ->get();
    }

    /**
     * Récupérer les sections
     */
    #[Computed]
    private function getSections()
    {
        return Section::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy('name')
            ->get();
    }

    /**
     * Récupérer les options selon la section sélectionnée
     */
    #[Computed]
    private function getOptions()
    {
        return $this->sectionId
            ? Option::where('section_id', $this->sectionId)->orderBy('name')->get()
            : collect();
    }

    /**
     * Récupérer les classes selon l'option sélectionnée
     */
    #[Computed]
    private function getClassRooms()
    {
        return $this->optionId
            ? ClassRoom::where('option_id', $this->optionId)->orderBy('name')->get()
            : collect();
    }
}
