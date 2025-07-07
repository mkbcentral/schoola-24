<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Models\CategoryFee;
use Livewire\Component;
use Livewire\WithPagination;

class ListReportPaymentByTranchPage extends Component
{
    use WithPagination;

    protected $listeners = [
        "selectedCategoryFee" => 'getSelectedCategoryFee'
    ];

    public int $selectedCategoryFeeId = 0;
    public ?CategoryFee $categoryFeeSelected = null;
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

    /**
     * Récupère la catégorie de frais sélectionnée
     */
    public function getSelectedCategoryFee(int $index): void
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::find($index) ?? null;
    }

    public function updatedMonthFilter(): void
    {
        $this->date_filter = '';
        $this->isByDate = false;
    }

    public function updatedDateFilter(): void
    {
        $this->month_filter = '';
        $this->isByDate = true;
    }

    public function updatedSectionFilter($val): void
    {
        $this->selectedSection = $val;
    }

    public function updatedOptionFilter($val): void
    {
        $this->selectedOption = $val;
    }

    public function updatedClassRoomFilter($val): void
    {
        $this->selectedClassRoom = $val;
    }

    /**
     * Initialise le composant avec la catégorie de frais sélectionnée
     */
    public function mount(int $categoryFeeId): void
    {
        $this->selectedCategoryFeeId = $categoryFeeId;
        $this->categoryFeeSelected = CategoryFee::find($categoryFeeId) ?? FeeDataConfiguration::getFirstCategoryFee();
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-report-payment-by-tranch-page', [
            'payments' => PaymentFeature::getList(
                $this->date_filter,
                $this->month_filter,
                null,
                $this->selectedCategoryFeeId,
                $this->scolary_fee_filter,
                $this->section_filter,
                $this->selectedOption,
                $this->class_room_filter,
                true,
                null,
                $this->per_page
            ),
            'scolarFees' => FeeDataConfiguration::getListScalarFee(
                $this->selectedCategoryFeeId,
                null,
                $this->class_room_filter,
                10
            ),
            'total_payments' => PaymentFeature::getTotal(
                $this->date_filter,
                $this->month_filter,
                $this->selectedCategoryFeeId,
                $this->scolary_fee_filter,
                $this->section_filter,
                $this->selectedOption,
                $this->class_room_filter,
                true,
                null,
                null,
                "CDF"
            ),
        ]);
    }
}
