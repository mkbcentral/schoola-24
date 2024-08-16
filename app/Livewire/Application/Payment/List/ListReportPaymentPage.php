<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Payment\PaymentFeature;
use App\Models\CategoryFee;
use Livewire\Component;
use Livewire\WithPagination;

class ListReportPaymentPage extends Component
{
    use WithPagination;

    public int $selectedCategoryFeeId = 0;
    public ?CategoryFee $categoryFeeSelected;
    public ?string $date_filter = '', $month_filter = '';
    public int $option_filter = 0, $section_filter = 0, $class_room_filter = 0, $scolary_fee_filter = 0;
    public int $selectedSection = 0, $selectedOption = 0, $selectedClassRoom = 0;
    public int $per_page = 10;

    protected $listeners = [
        "selectedCategoryFee" => 'getSelectedCategoryFee'
    ];

    /**
     * Recuprer le categorie de frais selectionné
     * @param int $index
     * @return void
     */
    public function getSelectedCategoryFee(int $index)
    {
        $this->selectedCategoryFeeId = $index;
        $this->categoryFeeSelected = CategoryFee::findOrFail($index);
    }

    public function updatedMonthFilter()
    {
        $this->date_filter = null;
    }
    public function updatedDateFilter()
    {
        $this->month_filter = "";
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
        $this->date_filter = date('Y-m-d');
        $this->categoryFeeSelected = CategoryFee::findOrFail($categoryFeeId);
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-report-payment-page', [
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
