<?php

namespace App\Http\Controllers\Api;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class PaymentRepportPaymentController extends Controller
{
    public function getPaymentbyDate(Request $request)
    {
        try {
            $categories = FeeDataConfiguration::getListCategoryFee(100);
            $payments = [];
            foreach ($categories as $category) {
                $payments[] = [
                    'name' => $category->name,
                    'amount' =>  PaymentFeature::getTotal(
                        $request->date,
                        null,
                        $category->id,
                        null,
                        null,
                        null,
                        null,
                        true,
                        'CDF'
                    ),
                    'currency' => $category->currency
                ];
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


    public function getPaymentbyMonth(Request $request)
    {
        try {
            $categories = FeeDataConfiguration::getListCategoryFee(100);
            $payments = [];
            foreach ($categories as $category) {
                $payments[] = [
                    'name' => $category->name,
                    'amount' =>  PaymentFeature::getTotal(
                        null,
                        $request->month,
                        $category->id,
                        null,
                        null,
                        null,
                        null,
                        true,
                        'CDF'
                    ),
                    'currency' => $category->currency
                ];
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
}
