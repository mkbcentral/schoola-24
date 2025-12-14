<?php

namespace App\Livewire\Application\Payment\List;

use App\Models\Registration;
use App\Models\Student;
use Livewire\Component;

class ListPaymentByStudent extends Component
{
    // listners
    protected $listeners = ['registrationPyaments' => 'getRegistrationPayments'];

    // student
    public Registration $registration;

    // payments
    public function getRegistrationPayments(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function render()
    {
        return view('livewire.application.payment.list.list-payment-by-student');
    }
}
