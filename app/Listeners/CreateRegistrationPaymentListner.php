<?php

namespace App\Listeners;

use App\Events\RegistrationCreatedEvent;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\ScolarFee;
use Illuminate\Support\Facades\Auth;

class CreateRegistrationPaymentListner
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     */
    public function handle(RegistrationCreatedEvent $event): void
    {
        $scolarFees = ScolarFee::query()
            ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
            ->where('category_fees.is_paid_for_registration', true)
            ->where('scolar_fees.class_room_id', $event->registration->classRoom->id)
            ->select('scolar_fees.*')
            ->get();
        foreach ($scolarFees as $scolarFee) {
            Payment::create([
                'payment_number' => rand(100, 1000),
                'month' => date('m'),
                'registration_id' => $event->registration->id,
                'scolar_fee_id' => $scolarFee->id,
                'rate_id' => Rate::DEFAULT_RATE_ID(),
                'user_id' => Auth::id(),
                'is_paid' => true
            ]);
        }
    }
}
