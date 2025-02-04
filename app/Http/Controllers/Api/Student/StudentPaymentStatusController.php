<?php

namespace App\Http\Controllers\Api\Student;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryFeeResource;
use App\Http\Resources\RegistrationResource;
use App\Http\Resources\ScolarFeeResource;
use App\Models\Registration;
use App\Models\ScolarFee;
use Exception;
use Illuminate\Http\Request;

class StudentPaymentStatusController extends Controller
{
    public function checkStudentHasPaid(
        string $code,
        int $categoryFeeId,
        string $month
    ) {
        try {
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
                    'month' => format_fr_month_name($month)
                ], 200);
            } else {
                return response()->json([
                    'message' => "Eleve introuvable"
                ], 404);
            }
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }

    public function getListCategoryFee()
    {
        try {
            $categoryFees = CategoryFeeResource::collection(FeeDataConfiguration::getListCategoryFee(100));
            return response()->json([
                'categories' => $categoryFees
            ]);
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }

    public  function  getScolarFeesByCategory(Request $request, $categoryFeeId, $registrationId)
    {
        try {
            $registration = Registration::find($registrationId);

            $scolarFee = ScolarFee::where('category_fee_id', $categoryFeeId)
                ->where('class_room_id', $registration->classRoom->id)
                ->first();
            if ($scolarFee == null) {
                return response()->json([
                    'message' => "Aucun frais scolaire trouvé pour cette catégorie"
                ], 404);
            } else {
                return response()->json([
                    'scolaryFee' => new ScolarFeeResource($scolarFee)
                ]);
            }
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }
}
