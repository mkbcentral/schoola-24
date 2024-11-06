<?php

namespace App\Http\Controllers\Api\Expense;

use App\Domain\Features\Finance\ExpenseFeeFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseFeeController extends Controller
{
    public function getExpenseByMonth(Request $request,string $month)
    {
        return $this->extracted(
            null,
            $month,
            $request->category_fee_id,
            $request->category_expense_id,
        );
    }

    public function getExpenseByDate(?Request $request,string $date)
    {
        return $this->extracted(
            $date,
            null,
            $request->category_fee_id,
            $request->category_expense_id,
        );
    }

    /**
     * @param string|null $date
     * @param string|null $month
     * @param int|null $categoryFeeId
     * @param int|null $categoryExpenseId
     * @return JsonResponse
     */
    public function extracted(?string $date,?string $month, ?int $categoryFeeId,?int $categoryExpenseId): \Illuminate\Http\JsonResponse
    {
        try {
            $amount_usd = ExpenseFeeFeature::getAmountTotal(
                $date,
                $month,
                $categoryFeeId,
                $categoryExpenseId,
                'USD',
            );
            $amount_cdf = ExpenseFeeFeature::getAmountTotal(
                $date,
                $month,
                $categoryFeeId,
                $categoryExpenseId,
                'CDF',
            );
            return response()->json([
                [
                    'amount' =>$amount_usd,
                    'currency' => 'USD',
                ],
                [
                    'amount' =>$amount_cdf,
                    'currency' => 'CDF',
                ]
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
}
