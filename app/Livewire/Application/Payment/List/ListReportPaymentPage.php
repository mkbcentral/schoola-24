<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Payment\PaymentFeature;
use Livewire\Component;

class ListReportPaymentPage extends Component
{

    public int $selectedCategoryFeeId = 0;
    public ?string $date_filter = '', $month_filter = '';
    public int $option_filter = 0, $section_filter = 0, $class_room_filter = 0, $scolary_fee_filter = 0;
    public int $selectedSection = 0, $selectedOption = 0, $selectedClassRoom = 0;

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
    }

    public function updatedMonthFilter()
    {
        $this->date_filter = null;
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
        $this->month_filter = date('m');
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-report-payment-page', [
            'payments' => PaymentFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->selectedCategoryFeeId,
                $this->scolary_fee_filter,
                $this->section_filter,
                $this->selectedOption,
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
                'CDF'
            ),
        ]);
    }
}
