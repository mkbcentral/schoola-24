<?php

namespace App\Http\Controllers\Api\Payment;

use App\Domain\Features\Payment\PaymentFeature;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\ScolarFee;
use Illuminate\Http\Request;

class MakePaymentController extends Controller
{
    public function __invoke(Request $request, string $id)
    {
        try {
            $inputs = $request->validate([
                'month' => 'required|string',
                'category_scolar_fee_id' => 'required|numeric',
            ]);
            $registration = Registration::query()->where('id', $id)->first();
            if ($registration) {
                $scolarFee = ScolarFee::query()->where(
                    'category_fee_id',
                    $inputs['category_scolar_fee_id']
                )
                    ->where('class_room_id', $registration->classRoom->id)
                    ->first();
                $existPayment = Payment::query()->where('registration_id', $registration->id)
                    ->where('month', $inputs['month'])
                    ->where('scolar_fee_id', $scolarFee->id)
                    ->first();
                if ($existPayment) {
                    return response([
                        'payment' => new PaymentResource($existPayment),
                        'message' => $registration->student->name . ' a déjà un paiement pour ce mois',
                        'status' => false,
                    ], 200);
                } else {
                    $inputs['registration_id'] = $registration->id;
                    $inputs['scolar_fee_id'] = $scolarFee->id;
                    $payment = PaymentFeature::create($inputs);
                    if ($payment) {
                        $payment->is_paid = true;
                        $payment->update();

                        return response([
                            'payment' => new PaymentResource($payment),
                            'message' => 'Paiment de ' . $registration->student->name . ' bien effectué',
                            'status' => true,
                        ], 200);
                    }
                }
            } else {
                return response([
                    'message' => 'Aucun élève trouvé ',
                ], 404);
            }
        } catch (\Exception $ex) {
            return response([
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
}
