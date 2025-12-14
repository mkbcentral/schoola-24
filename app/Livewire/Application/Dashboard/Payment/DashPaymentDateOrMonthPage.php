<?php

namespace App\Livewire\Application\Dashboard\Payment;

use App\Models\Payment;
use Livewire\Component;

class DashPaymentDateOrMonthPage extends Component
{
    public $date;

    public $month;

    public function updatedDate($d)
    {
        $this->date = $d;
        $this->month = null;
    }

    public function updatedMonth($m)
    {
        $this->month = $m;
        $this->date = null;
    }

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->month = null;
    }

    public function render()
    {
        return view('livewire.application.dashboard.payment.dash-payment-date-or-month-page', [
            'payments' => Payment::getTotalAmountByCategoryForMonthOrDate(
                $this->month,
                $this->date,
            ),
        ]);
    }
}
