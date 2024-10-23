<?php

namespace App\Http\Controllers\Api;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFeeResource;
use App\Http\Resources\RegistrationResource;
use App\Models\Registration;

class StudentPaymentStatusController extends Controller
{
    public function checkStudentHasPaid(
        string $code,
        int $categoryFeeId,
        string $month
    ) {
        $status = false;
        $registration = Registration::where('code', $code)->first();
        if ($registration) {
            $payment = PaymentFeature::getSinglePaymentForStudentWithMonth(
                $registration->id,
                $categoryFeeId,
                $month
            );
            if ($payment) {
                $status = true;
            }
            return response()->json([
                'student' => new RegistrationResource($registration),
                'status' => $status,
            ], 200);

        } else {
            return response()->json([
                'message' => "Eleve introuvable"
            ], 404);
        }
    }

    public function getListCategoryFee()
    {
        $categoryFees = CategoryFeeResource::collection(FeeDataConfiguration::getListCategoryFee(100));
        return response()->json([
            'categories' => $categoryFees
        ]);
    }
}
