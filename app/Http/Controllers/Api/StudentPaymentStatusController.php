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
    public function checkStudentHasPaied(
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
            } else {
                $status = false;
            }
            if ($status == true) {
                return response()->json([
                    'student' => new RegistrationResource($registration),
                    'mesage' => "En ordre",
                    'status' => $status,
                ]);
            } else {
                return response()->json([
                    'student' => new RegistrationResource($registration),
                    'mesage' => "Pas en ordre",
                    'status' => $status
                ]);
            }
        } else {
            return response()->json([
                'error' => "Eleve introuvable"
            ]);
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
