<?php

namespace App\Http\Controllers\Api;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFeeResource;
use App\Models\Registration;
use Illuminate\Http\Request;

class StudentPaymentStatusController extends Controller
{
    public function checkStudentHasPaied(Request $request)
    {
        $status = false;
        $registration = Registration::where('code', $request->code)->first();
        $payment = PaymentFeature::getSinglePaymentForStudentWithMonth(
            $registration->id,
            $request->category_fee_id,
            $request->month
        );
        if ($payment) {
            $status = true;
        } else {
            $status = false;
        }
        if ($status == true) {
            return response()->json([
                'mesage' => "En ordre",
                'status' => $status
            ]);
        } else {
            return response()->json([
                'mesage' => "Pas en ordre",
                'status' => $status
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
