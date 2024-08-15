<?php

namespace App\Livewire\Application\Payment\Reguralization\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\FormPaymentRegularization;
use App\Models\PaymentRegularization;
use Exception;
use Livewire\Component;

class FormRegularizationPage extends Component
{

    protected $listeners = [
        "paymentRegularizationData" => "getPaymentRegularization",
        "paymentRegularizationFormReseted" => "clearnFormData",
    ];
    public ?PaymentRegularization $paymentRegularization = null;
    public FormPaymentRegularization $form;
    public int $selectedOptionId = 0;

    public function clearnFormData()
    {
        $this->paymentRegularization = null;
        $this->form->reset();
        $this->form->created_at = date('Y-m-d');
    }

    public function updatedFormOptionId($val)
    {
        $this->selectedOptionId = $val;
    }

    public function getPaymentRegularization(PaymentRegularization $paymentRegularization)
    {
        $this->paymentRegularization = $paymentRegularization;
        $this->form->created_at = $paymentRegularization->created_at->format('Y-m-d');
        $this->form->name = $paymentRegularization->name;
        $this->form->month = $paymentRegularization->month;
        $this->form->amount = $paymentRegularization->amount;
        $this->form->category_fee_id = $paymentRegularization->category_fee_id;
        $this->form->option_id = $paymentRegularization->classRoom->option_id;
        $this->selectedOptionId = $paymentRegularization->classRoom->option_id;
        $this->form->class_room_id = $paymentRegularization->class_room_id;
    }

    public function save()
    {
        $this->validate();
        try {
            $this->form->create();
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $this->validate();
        try {
            $this->form->update($this->paymentRegularization);
            $this->dispatch('updated', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->paymentRegularization == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('paymentRegularizationListRefreshed');
        $this->form->reset();
        $this->paymentRegularization = null;
        $this->form->created_at = date('Y-m-d');
    }

    public function mount()
    {
        $this->form->created_at = date('Y-m-d');
    }

    public function render()
    {
        return view('livewire.application.payment.reguralization.form.form-regularization-page');
    }
}
