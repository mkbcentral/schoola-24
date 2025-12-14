<?php

namespace App\Livewire\Application\Payment\List;

use App\Models\Registration;
use Livewire\Component;

class ListStudentForPaymentPage extends Component
{
    public $registration_id = 0;

    public function updatedRegistrationId($val)
    {
        $registration = Registration::find($val);
        if ($registration) {
            // open modal
            $this->dispatch('open-form-payment');
            $this->dispatch('registrationData', $registration);
            $this->dispatch('registrationPyaments', $registration);
        }
    }

    public function render()
    {

        return view('livewire.application.payment.list.list-student-for-payment-page');
    }
}
