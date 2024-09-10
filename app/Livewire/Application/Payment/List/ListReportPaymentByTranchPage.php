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
    public ?int $per_page = 20;
    public bool $isByDate = true;


    /**
     * Recuprer le categorie de frais selectionné
     * @param int $index
     * @return void
     */
    public function getSelectedCategoryFee(int $index)
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::find($index);
    }

    public function updatedMonthFilter()
    {
        $this->date_filter = '';
        $this->isByDate = false;
    }
    public function updatedDateFilter()
    {
        $this->month_filter = "";
        $this->isByDate = true;
    }
    /**
     * Summary of updatedSectionFilter
     * @param mixed $val
     * @return void
     */
    public function updatedSectionFilter($val)
    {
        $this->selectedSection = $val;
    }

    /**
     * Summary of updatedOptionFilter
     * @param mixed $val
     * @return void
     */
    public function updatedOptionFilter($val)
    {
        $this->selectedOption = $val;
    }
    public function updatedClassRoomFilter($val)
    {
        $this->selectedClassRoom = $val;
    }
    /**
     * Summary of mount
     * @param int $categoryFeeId
     * @return void
     */
    public function mount(int $categoryFeeId)
    {
        $this->selectedCategoryFeeId = $categoryFeeId;
        $categoryFee = FeeDataConfiguration::getListCategoryFeeForCurrentSchool();
        $this->categoryFeeSelected = $categoryFee;
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
                "CDF"
            ),
        ]);
    }
}
