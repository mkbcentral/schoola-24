<?php

namespace App\Domain\Features\Payment;

use App\Models\Payment;
use App\Models\Rate;
use App\Models\Registration;
use App\Models\ScolarFee;
use Illuminate\Support\Facades\Auth;

class OtherPaymentFeature
{
    public static function createPaymentForRegistration(Registration $registration)
    {
        $scolarFees = ScolarFee::query()
            ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
            ->where('category_fees.is_paid_for_registration', true)
            ->where('scolar_fees.class_room_id', $registration->classRoom->id)
            ->select('scolar_fees.*')
            ->where('is_changed', false)
            ->get();
        foreach ($scolarFees as $scolarFee) {
            Payment::create([
                'payment_number' => rand(100, 1000),
                'month' => date('m'),
                'registration_id' => $registration->id,
                'scolar_fee_id' => $scolarFee->id,
                'rate_id' => Rate::DEFAULT_RATE_ID(),
                'user_id' => Auth::id(),
            ]);
        }
    }
}
