<?php

namespace App\Livewire\Application\Fee\Registration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RegistrationFeeForm;
use App\Models\RegistrationFee;
use Exception;
use Livewire\Component;

class FormRegistrationFeePage extends Component
{
    protected $listeners = [
        "RegistrationFeeData" => "getRegistrationFee",
    ];
    public ?RegistrationFee $registrationFeeSelected = null;
    public RegistrationFeeForm $form;

    public function getRegistrationFee(RegistrationFee $RegistrationFee)
    {

        $this->registrationFeeSelected = $RegistrationFee;
        $this->form->fill($RegistrationFee->toArray());
    }

    public function save()
    {
        $input = $this->validate();
        try {
            $this->form->create($input);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function update()
    {
        $input = $this->validate();
        try {
            $this->form->update($this->registrationFeeSelected, $input);
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function handlerSubmit()
    {
        if ($this->registrationFeeSelected == null) {
            $this->save();
        } else {
            $this->update();
        }
        $this->dispatch('regFeeDataRefreshed');
        $this->form->reset();
    }

    public function cancelUpdate()
    {
        $this->registrationFeeSelected = null;
        $this->form->reset();
    }

    public function render()
    {
        return view('livewire.application.fee.registration.form.form-registration-fee-page');
    }
}
