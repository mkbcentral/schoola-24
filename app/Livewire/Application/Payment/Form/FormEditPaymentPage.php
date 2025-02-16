<?php

namespace App\Livewire\Application\Payment\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\PaymentForm;
use App\Models\Payment;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use Exception;
use Livewire\Component;

class FormEditPaymentPage extends Component
{
    protected $listeners = [
        "paymentData" => "getPayment",
    ];
    public PaymentForm $form;
    public ?Payment $payment = null;
    public $selectedCategoryFeeId, $selectedIdClassRoom = 0, $scolar_fee_id = 0;


    public function getPayment(?Payment $payment)
    {
        $this->selectedCategoryFeeId = $payment->scolarFee->category_fee_id;
        $this->selectedIdClassRoom = $payment->registration->class_room_id;
        $this->payment = $payment;
        $this->form->month = $payment->month;
        $this->form->category_fee_id = $payment->scolarFee->category_fee_id;
        $this->scolar_fee_id = $payment->scolar_fee_id;
        $this->form->created_at = $payment->created_at->format('Y-m-d');
    }

    public function updatedFormCategoryFeeId($val)
    {
        $this->selectedCategoryFeeId = $val;
    }

    public function update()
    {
        $this->validate();
        try {
            $this->form->update($this->payment, $this->scolar_fee_id);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            $this->dispatch('refreshPaymentList');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->selectedCategoryFeeId = $this->payment?->scolarFee?->category_fee_id;
    }
    public function render()
    {
        return view('livewire.application.payment.form.form-edit-payment-page', [
            'scolarFees' => ScolarFee::query()
                ->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
                ->where('category_fees.school_id', School::DEFAULT_SCHOOL_ID())
                ->where('category_fees.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('scolar_fees.category_fee_id', $this->selectedCategoryFeeId)
                ->where('scolar_fees.class_room_id', $this->selectedIdClassRoom)
                ->where('scolar_fees.is_changed', false)
                ->select('scolar_fees.*')
                ->get()
        ]);
    }
}
