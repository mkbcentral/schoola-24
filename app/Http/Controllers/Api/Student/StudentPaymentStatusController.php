<?php

namespace App\Http\Controllers\Api\Student;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFeeResource;
use App\Http\Resources\RegistrationResource;
use App\Http\Resources\ScolarFeeResource;
use App\Models\Registration;
use Illuminate\Http\Request;

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
                'month'=>format_fr_month_name($month)
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

    public  function  getScolarFeesByCategory(Request $request)
    {
        $registration=Registration::where('code',$request->code)->first();
        $scolarFees=FeeDataConfiguration::getListScalarFeeNotPaginate(
            $request->category_fee_id,
            $registration->classRoom->option->id,
            $registration->classRoom->id
            );
        return response()->json([
            'scolaryFees' => ScolarFeeResource::collection($scolarFees)
        ]);
    }
}
