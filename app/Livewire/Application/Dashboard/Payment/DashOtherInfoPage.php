<?php

namespace App\Livewire\Application\Dashboard\Payment;

use App\Models\ExpenseFee;
use App\Models\OtherExpense;
use App\Models\Payment;
use App\Models\Registration;
use Livewire\Component;

class DashOtherInfoPage extends Component
{
    public function mount() {}
    public function render()
    {
        return view('livewire.application.dashboard.payment.dash-other-info-page', [

            'revenue' => Payment::join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
                ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
                ->join('rates', 'payments.rate_id', 'rates.id')
                ->where('category_fees.id', 1) // Remplacez 1 par l'ID de la catégorie souhaitée
                ->where('payments.is_paid', true)
                ->sum('scolar_fees.amount'),
            'expense' => ExpenseFee::getTotalExpenses()->first(),
            'otherExpense' => OtherExpense::getTotalExpenses()->first(),
            'count_all' => Registration::count(),
        ]);
    }
}
