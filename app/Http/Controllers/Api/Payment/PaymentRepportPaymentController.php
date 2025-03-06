<?php

namespace App\Http\Controllers\Api\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Registration;
use Exception;
use Illuminate\Http\Request;

class PaymentRepportPaymentController extends Controller
{
    /**
     * Recuprer les paiments par jour
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getPaymentByDate(Request $request)
    {
        try {
            $categories = FeeDataConfiguration::getListCategoryFee(100);
            $payments = [];

            foreach ($categories as $category) {
                $amount = PaymentFeature::getTotal(
                    $request->date,
                    null,
                    $category->id,
                    null,
                    null,
                    null,
                    null,
                    true,
                    null,
                    null,
                    'CDF'
                );
                if ($amount > 0) {
                    $payments[] = [
                        'name' => $category->name,
                        'amount' => $amount,
                        'currency' => $category->currency
                    ];
                }
            }
            return response()->json([
                'payments' => $payments
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
    /**
     * Recuperer le spaiment par mois
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getPaymentByMonth(Request $request)
    {
        try {
            $categories = FeeDataConfiguration::getListCategoryFee(100);
            $payments = [];
            foreach ($categories as $category) {
                $amount = PaymentFeature::getTotal(
                    null,
                    $request->month,
                    $category->id,
                    null,
                    null,
                    null,
                    null,
                    true,
                    null,
                    null,
                    'CDF'
                );
                if ($amount > 0) {
                    $payments[] = [
                        'name' => $category->name,
                        'amount' =>  $amount,
                        'currency' => $category->currency
                    ];
                }
            }
            return response()->json([
                'payments' => $payments
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }

    public function getStudentPayment(Request $request, string $code)
    {
        try {
            $registration = Registration::query()->where('code', $code)->first();
            if (!$registration) {
                return response()->json([
                    'message' => 'Elève introuvable'
                ], 404);
            } else {
                $paymentData = [];
                $payments
                    = $registration->payments()
                    ->get();
                foreach ($payments as $payment) {
                    $paymentData[] = [
                        'date' => $payment->created_at->format('d/m/Y'),
                        'fee' => $payment->scolarFee->name,
                        'amount' => format_fr_month_name($payment->month)
                    ];
                }
                return response()->json([
                    'payments' => $paymentData
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }

    public  function  getStudentPayments(Request $request, Registration $registration)
    {
        // api/student/paymentp-infos/{registration}
        try {
            $payments = $registration->payments()->orderBy('created_at', 'desc')->get();
            return response()->json([
                'payments' => PaymentResource::collection($payments)
            ]);
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }
}
