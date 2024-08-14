<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Payment\PaymentFeature;
use App\Domain\Utils\AppMessage;
use App\Models\CategoryFee;
use App\Models\Payment;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListPaymentByDatePage extends Component
{
    use WithPagination;
    protected $listeners = [
        "refreshPaymentList" => '$refresh',
    ];
    public ?string $date_filter = '';
    public int $category_fee_filter = 0;
    public ?CategoryFee $categoryFeeSelected;
    #[Url(as: 'q')]
    public $q = '';

    public function mount()
    {
        $this->date_filter = date('Y-m-d');
        $this->category_fee_filter = CategoryFee::firstOrFail()->id;
        $this->categoryFeeSelected = CategoryFee::firstOrFail();
    }

    public function updatedCategoryFeeFilter($val)
    {
        $this->categoryFeeSelected = CategoryFee::findOrFail($val);
    }

    public function edit(Payment $payment)
    {
        $this->dispatch('paymentData', $payment);
    }

    public function delete(?Payment $payment)
    {
        try {
            if ($payment->is_paid == false) {
                $payment->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => 'Action impossible, car le paiement est déjà validé']);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function makeIsPaid(?Payment $payment)
    {
        try {
            if ($payment->is_paid == true) {
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

    public function render()
    {
        return view('livewire.application.payment.list.list-payment-by-date-page', [
            'payments' => PaymentFeature::getList(
                $this->date_filter,
                '',
                $this->q,
                $this->category_fee_filter,
                null,
                null,
                null,
                null,
                null,
                100
            ),
            'total_payments' => PaymentFeature::getTotal(
                $this->date_filter,
                '',
                $this->category_fee_filter,
                null,
                null,
                null,
                0,
                true,
                'CDF'
            ),
            'categoryFees' => CategoryFee::all()
        ]);
    }
}
