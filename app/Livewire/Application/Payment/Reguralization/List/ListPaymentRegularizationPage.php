<?php

namespace App\Livewire\Application\Payment\Reguralization\List;

use App\Domain\Features\Payment\PaymentRegularizationFeature;
use App\Domain\Utils\AppMessage;
use App\Models\CategoryFee;
use App\Models\PaymentRegularization;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListPaymentRegularizationPage extends Component
{
    protected $listeners = ['paymentRegularizationListRefreshed', '$refresh'];
    use WithPagination;
    public int $per_page = 10, $selectedOption = 0;
    public int $option_filter = 0, $class_room_filter = 0, $category_fee_filter = 0;
    public ?string $date_filter = '', $month_filter = '', $currency = '';
    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'payment_regularizations.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
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
     * Summary of updatedOptionFilter
     * @param mixed $val
     * @return void
     */
    public function updatedCategoryFeeFilter($val)
    {
        $categoryFee = CategoryFee::findOrFail($val);
        $this->category_fee_filter = $categoryFee->id;
        $this->currency = $categoryFee->currency;
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


    public function newPayment()
    {
        $this->dispatch('paymentRegularizationFormReseted');
    }

    public function edit(?PaymentRegularization $paymentRegularization)
    {
        $this->dispatch('paymentRegularizationData', $paymentRegularization);
    }

    public function delete(?PaymentRegularization $paymentRegularization)
    {
        try {
            $paymentRegularization->delete();
            $this->dispatch('updated', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->date_filter = date('Y-m-d');
        $categoryFee = CategoryFee::firstOrFail();
        $this->category_fee_filter = $categoryFee->id;
        $this->currency = $categoryFee->currency;
    }

    public function render()
    {
        return view('livewire.application.payment.reguralization.list.list-payment-regularization-page', [
            'paymentsRegularizations' => PaymentRegularizationFeature::getList(
                $this->date_filter,
                $this->month_filter,
                $this->q,
                $this->category_fee_filter,
                $this->option_filter,
                $this->class_room_filter,
                $this->sortBy,
                $this->sortAsc,
                $this->per_page
            ),
            'total' => PaymentRegularizationFeature::getAmountTotal(
                $this->date_filter,
                $this->month_filter,
                $this->q,
                $this->category_fee_filter,
                $this->option_filter,
                $this->class_room_filter
            )
        ]);
    }
}
