<?php

namespace App\Livewire\Application\Payment\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\PaymentForm;
use App\Models\Registration;
use Exception;
use Livewire\Component;

class FormPaymentPage extends Component
{
    protected $listeners = [
        "registrationData" => "getRegistration",
    ];
    public ?Registration $registration;
    public $selectedCategoryFeeId, $selectedIdClassRoom = 0;
    public PaymentForm $form;
    public function updatedFormCategoryFeeId($val)
    {
        $this->selectedCategoryFeeId = $val;
    }
    public function getRegistration(?Registration $registration)
    {
        $this->registration = $registration;
        $this->selectedIdClassRoom = $registration->classRoom->id;
    }
    public function save()
    {
        $this->validate();
        try {
            $registration = $this->form->create($this->registration->id);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }


    public function render()
    {
        return view('livewire.application.payment.form.form-payment-page');
    }
}
