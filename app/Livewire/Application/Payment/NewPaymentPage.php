<?php

namespace App\Livewire\Application\Payment;

use Livewire\Component;

class NewPaymentPage extends Component
{
    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.payment.new-payment-page');
    }
}
