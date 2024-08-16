<?php

namespace App\Livewire\Application\Payment\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\PaymentForm;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\ScolarFee;
use Exception;
use Livewire\Component;

class FormPaymentPage extends Component
{
    protected $listeners = [
        "registrationData" => "getRegistration",
    ];
    public ?Registration $registration;
    public $selectedCategoryFeeId, $selectedIdClassRoom = 0;
    public ?ScolarFee $scolarFee = null;

    public PaymentForm $form;
    public function updatedFormCategoryFeeId($val)
    {
        $this->selectedCategoryFeeId = $val;
        $this->scolarFee = ScolarFee::query()
            ->where('category_fee_id', $val)
            ->where('class_room_id', $this->registration->class_room_id)
            ->first();
    }
    public function getRegistration(?Registration $registration)
    {
        $this->registration = $registration;
        $this->selectedIdClassRoom = $registration->classRoom->id;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }
    public function save()
    {
        $this->validate();
        try {
            $this->form->create($this->registration->id);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->dispatch('refreshPaymentList');
            $this->form->reset();
            $this->form->created_at = date('Y-m-d');
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function mount()
    {
        $this->form->created_at = date('Y-m-d');
    }


    public function render()
    {
        return view('livewire.application.payment.form.form-payment-page');
    }
}
