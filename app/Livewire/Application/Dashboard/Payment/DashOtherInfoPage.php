<?php

namespace App\Livewire\Application\Dashboard\Payment;

use App\Models\CategoryFee;
use App\Models\ExpenseFee;
use App\Models\OtherExpense;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SchoolYear;
use Livewire\Component;

class DashOtherInfoPage extends Component
{
    public function mount() {}
    public function render()
    {
        $selectedScolar = CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())->first();
        return view('livewire.application.dashboard.payment.dash-other-info-page', [
            'revenue' => Payment::join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
                ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
                ->join('rates', 'payments.rate_id', 'rates.id')
                ->join('registrations', 'payments.registration_id', 'registrations.id')
                ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->where('category_fees.id', $selectedScolar->id) // Remplacez 1 par l'ID de la catégorie souhaitée
                ->where('payments.is_paid', true)
                ->sum('scolar_fees.amount'),
            'expense' => ExpenseFee::getTotalExpenses()->first(),
            'otherExpense' => OtherExpense::getTotalExpenses()->first(),
            'count_all' => Registration::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())->count(),
            'registrations' => Registration::selectRaw('is_old, COUNT(*) as total, CASE WHEN is_old = true THEN "ancien" ELSE "nouveau" END as status')
                ->groupBy('is_old')
                ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                ->get(),
        ]);
    }
}
