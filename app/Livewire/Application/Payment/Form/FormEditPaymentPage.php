<?php

namespace App\Livewire\Application\Payment\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\PaymentForm;
use App\Models\Payment;
use Exception;
use Livewire\Component;

class FormEditPaymentPage extends Component
{
    protected $listeners = [
        "paymentData" => "getPayment",
    ];
    public PaymentForm $form;
    public ?Payment $payment = null;
    public $selectedCategoryFeeId, $selectedIdClassRoom = 0;


    public function getPayment(?Payment $payment)
    {
        $this->selectedCategoryFeeId = $payment->scolarFee->category_fee_id;
        $this->selectedIdClassRoom = $payment->registration->class_room_id;
        $this->payment = $payment;
        $this->form->month = $payment->month;
        $this->form->category_fee_id = $payment->scolarFee->category_fee_id;
        $this->form->scolar_fee_id = $payment->scolar_fee_id;
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
            $this->form->update($this->payment);
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
        return view('livewire.application.payment.form.form-edit-payment-page');
    }
}
